<?php

namespace App\Http\Controllers;
use App\LocalCell;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MyFunc;

class LocalController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        return view('local',compact('id'));
    }
    public function first(Request $request)
    {
        $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        $param = [
            'cell_name'         => $localCell->cell_name,
            'cell_code'         => $localCell->cell_code,
            'cell_colors'        => explode(',', $localCell->cell_colors, config('CONST.LOCAL.MAX_CELL_NUM')),
            'MAX_CELL_ROW_NUM'  => config('CONST.LOCAL.MAX_CELL_ROW_NUM'),
            'MAX_CELL_COL_NUM'  => config('CONST.LOCAL.MAX_CELL_COL_NUM'),
            'MAX_CELL_NUM'      => config('CONST.LOCAL.MAX_CELL_NUM'),
        ];
        // log::debug($localCell->cell_code);
        return $param;
    }
    public function calc(Request $request)
    {
        // exec('sudo docker exec -i 804028b02ec5 python tmp/hello.py', $output, $status);

        // $dummyCellColorsData = [];
        // for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
        //     $dummyCellColorsData[$i] =
        //          '#'
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         ;
        // }

        $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();

        Storage::delete(Auth::user()->name . '/code/code.py');
        Storage::put(Auth::user()->name . '/code/code.py', $localCell->cell_code);

        // $dockerRunCmd =
        //     'sudo docker create -i '.
        //     '--net none '.
        //     '--cpuset-cpus 0 '.
        //     '--memory 32m --memory-swap 32m '.
        //     '--ulimit nproc=1:1 '.
        //     '--ulimit fsize=1000000 '.
        //     'dockerworkspace_dev';
        // $dockerContainerId = exec($dockerRunCmd, $dockerRunCmdOutput, $dockerRunCmdStatus);
        $dockerContainerId ='d1ade3c7b764';




        $cdCmd = 'cd /tmp';
        $rmCmd = 'rm -Rf '. Auth::user()->name;
        $mkdirCmd = 'mkdir -p ' . Auth::user()->name . '/code';
        $dockerExecRmAndMkdirCmd =
            'sudo docker exec '. $dockerContainerId .
            ' bash -c "' . $cdCmd . ' && ' . $rmCmd .  ' && '. $mkdirCmd . '"';
        exec($dockerExecRmAndMkdirCmd, $dockerExecRmAndMkdirCmdOutput, $dockerExecRmAndMkdirCmdStatus);

        log::debug($dockerExecRmAndMkdirCmd);

        $storagePath = storage_path('app/' . Auth::user()->name . '/code/code.py ');
        $devConteinerPath = ':/tmp/'. Auth::user()->name . '/code';
        $dockerCpCmd = 'sudo docker cp ' . $storagePath . $dockerContainerId .$devConteinerPath;
        exec($dockerCpCmd, $dockerCpCmdOutput, $dockerCpCmdStatus);

        log::debug($dockerCpCmd);

        $codeExeCmd =
            'sudo docker exec '. $dockerContainerId . ' bash -c "cd /tmp/' . Auth::user()->name .'/code && timeout 1 python code.py" 2>&1';
        exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);
  
        log::debug($codeExeCmd);
        log::debug($codeExeCmdOutput);
        log::debug($codeExeCmdStatus);

        if(is_array($codeExeCmdOutput)) {
            $cellColorsCount = count($codeExeCmdOutput);
        }
        else {
            $cellColorsCount = 0;
        }
        
        $isCellColorsCountOk =  $cellColorsCount === config('CONST.LOCAL.MAX_CELL_COL_NUM');
        $isCellColorsContentsOk = true;
        if($isCellColorsCountOk) {
            foreach($codeExeCmdOutput as $cellColor) {
                $isColorCode = preg_match('/^#[\da-fA-F]{6}$/', $cellColor);
                if($isColorCode) {
                    //正常。何もしない。
                }
                else {
                    $isCellColorsContentsOk = false;
                    break;
                }
            }
        }

        $isUpdateCellColors = $isCellColorsCountOk && $isCellColorsContentsOk;
        if($isUpdateCellColors) {
            $sendCellColors = $codeExeCmdOutput;
        }
        else {
            $sendCellColors = $request->cell_colors;
        }

        log::debug('$isCellColorsCountOk:' . $isCellColorsCountOk);
        log::debug('$isCellColorsContentsOk:' . $isCellColorsContentsOk);
        log::debug($isUpdateCellColors);
        log::debug($sendCellColors);

        $param = [
            'cell_colors'           => $sendCellColors,
            'code_exec_cmd_output'  => $codeExeCmdOutput,
            'code_exec_cmd_status'  => $codeExeCmdStatus,
        ];

        return $param;

    }
    public function codesave(Request $request)
    {
        $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        log::debug($localCell);
        log::debug($request->cell_code);
        $localCell->cell_code = $request->cell_code;
        $localCell->save();
        return ["cellCodeSaveSuccess"];
    }

    public function cellcolorsave(Request $request)
    {


        $thumbnailFileName    = 'thumbnail_'  . Auth::user()->name . '_'. $request->id . '.jpg';
        $detailsFileName      = 'details_'    . Auth::user()->name . '_'. $request->id . '.jpg';

        // $requestCellCollor = explode(',', $request->cell_colors);

        MyFunc::createCellColorsJpg(
            $thumbnailFileName,
            config('CONST.LOCAL.THUMBNAIL_HEIGHT'),
            config('CONST.LOCAL.THUMBNAIL_WIDTH'),
            $request->cell_colors
        );
        MyFunc::createCellColorsJpg(
            $detailsFileName,
            config('CONST.LOCAL.DETAILS_HEIGHT'),
            config('CONST.LOCAL.DETAILS_WIDTH'),
            $request->cell_colors
        );


        $localCell = LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        // log::debug($request->cell_colors);
        $localCell->cell_colors = $request->cell_colors;
        $localCell->thumbnail_filename = $thumbnailFileName;
        $localCell->detail_filename    = $detailsFileName;
        $localCell->save();

        return ["cellColorsSaveSuccess"];
    }
    public function change(Request $request)
    {
        return ["cellCodeChangeSuccess"];
    }
}






// public function get(Request $request)
// {
//     return $num = $request->num + 10;
// }
// public function run(Request $request)
// {
//     $test1 = exec('sudo docker ps', $test2, $test3);
//     var_dump($test1);
//     var_dump($test2);
//     var_dump($test3);
//     return view('local',compact('test1','test2','test3'));
// }
// public function stop(Request $request)
// {
//     // $dockerCmd =
//     // 'sudo docker create'

//     $test1 = exec("sudo docker exec -i 804028b02ec5 python tmp/hello.py", $test2, $test3);

//     //log::debug($test2);
//     // return $num = $request->num + 1;
//     return $num = $test2;
// }
// public function save(Request $request)
// {
//     return ["tanuki"];
// }



// public function save(Request $request)
// {
//     // $dockerCmd =
//     // 'sudo docker create'

//     $test1 = exec("sudo docker exec -i 804028b02ec5 python tmp/hello.py", $test2, $test3);

//     //log::debug($test2);
//     // return $num = $request->num + 1;
//     return $num = $test2;
// }


// $codeExeCmd =
// 'sudo docker start -i'. $dockerContainerId . ";"
// // 'sudo docker exec -i ' . $dockerContainerId . ' ls';
// // .'sudo docker exec -i '. $dockerContainerId .' sh -c "'
// .'sh -c "'
// .'cd /tmp;'
// .'rm work.py;'
// .'touch work.py;'
// // // .'echo "input_cell_colors = ' . $request->cell_colors . '.split(",")"'
// // // .'echo "' . $localCell->cell_code . '" >> work.py;'
// // // .'echo "print(",".join(output_cell_colors))"'
// // . 'echo \"print(12345)\" >> work.py;'
// . 'echo \"' . str_replace('"', '\'', $localCell->cell_code) . '\" >> work.py;'
// . 'timeout 1 python work.py;'
// .'" 2>&1';















        // // exec('sudo docker exec -i 804028b02ec5 python tmp/hello.py', $output, $status);

        // // $dummyCellColorsData = [];
        // // for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
        // //     $dummyCellColorsData[$i] =
        // //          '#'
        // //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        // //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        // //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        // //         ;
        // // }

        // $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();

        // $dockerRunCmd =
        //     'sudo docker create -i '.
        //     '--net none '.
        //     '--cpuset-cpus0 '.
        //     '--memory 256m --memory-swap 256m '.
        //     '--ulimit nproc=1:1 '.
        //     '--ulimit fsize=1000000 '.
        //     'dockerworkspace_dev';
        // $dockerContainerId = exec($dockerRunCmd, $dockerRunCmdOutput, $dockerRunCmdStatus);

        // $codeExeCmd =
        //     'sudo docker start -i'. $dockerContainerId . ";"
        //     .'sh -c "'
        //     .'cd /tmp;'
        //     .'rm work.py;'
        //     .'touch work.py;'
        //     . 'echo \"' . 'cell_colorss = \'' . $request->cell_colors . '\'.strip(\'[\'\']\').split(\',\')\" >> work.py;'
        //     // . 'echo \"' . 'cell_colorss =  ' . str_replace('"', '\'\'', $request->cell_colors) . '\" >> work.py;'
        //     . 'echo \"' . str_replace('"', '\'', $localCell->cell_code) . '\" >> work.py;'
        //     . 'timeout 1 python work.py;'
        //     .'" 2>&1';
        // exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);

        // $dockerRmCmd = 'sudo docker stop ' . $dockerContainerId . ';' . 'sudo docker rm ' . $dockerContainerId . ';';
        // exec($dockerRmCmd);

        // log::debug($request->cell_colors);
        // log::debug($codeExeCmdOutput);
        // log::debug($codeExeCmdStatus);

        // $param = [
        //     'cell_colors'            => $codeExeCmdOutput,
        //     'code_exec_cmd_output'  => $codeExeCmdOutput,
        //     'code_exec_cmd_status'  => $codeExeCmdStatus,
        // ];

        // return $param;











        
            // $codeExeCmd =
            //     'sudo docker start -i'. $dockerContainerId . ";"
            //     .'sh -c "'
            //     .'cd /tmp;'
            //     . 'timeout 1 python code.py;'
            //     .'" 2>&1';
            // exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);


            // // log::debug($request->cell_colors);
            // log::debug($codeExeCmdOutput);
            // log::debug($codeExeCmdStatus);

        // $codeExeCmd =
        //     'sudo docker start -i'. $dockerContainerId . ";"
        //     .'sh -c "'
        //     .'cd /tmp;'
        //     .'rm work.py;'
        //     .'touch work.py;'
        //     . 'echo \"' . 'cell_colorss = \'' . $request->cell_colors . '\'.strip(\'[\'\']\').split(\',\')\" >> work.py;'
        //     // . 'echo \"' . 'cell_colorss =  ' . str_replace('"', '\'\'', $request->cell_colors) . '\" >> work.py;'
        //     . 'echo \"' . str_replace('"', '\'', $localCell->cell_code) . '\" >> work.py;'
        //     . 'timeout 1 python work.py;'
        //     .'" 2>&1';
        // exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);

            // $dockerRmCmd = 'sudo docker stop ' . $dockerContainerId . ';' . 'sudo docker rm ' . $dockerContainerId . ';';
            // exec($dockerRmCmd);

        // log::debug($request->cell_colors);
        // log::debug($codeExeCmdOutput);
        // log::debug($codeExeCmdStatus);

        // $param = [
        //     'cell_colors'            => $codeExeCmdOutput,
        //     'code_exec_cmd_output'  => $codeExeCmdOutput,
        //     'code_exec_cmd_status'  => $codeExeCmdStatus,
        // ];









                // $codeExeCmd =
        //     'sudo docker start -i'. $dockerContainerId . ";"
        //     .'sh -c "'
        //     .'cd /tmp;'
        //     .'rm work.py;'
        //     .'touch work.py;'
        //     . 'echo \"' . 'cell_colorss = \'' . $request->cell_colors . '\'.strip(\'[\'\']\').split(\',\')\" >> work.py;'
        //     // . 'echo \"' . 'cell_colorss =  ' . str_replace('"', '\'\'', $request->cell_colors) . '\" >> work.py;'
        //     . 'echo \"' . str_replace('"', '\'', $localCell->cell_code) . '\" >> work.py;'
        //     . 'timeout 1 python work.py;'
        //     .'" 2>&1';
        // exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);

            // $dockerRmCmd = 'sudo docker stop ' . $dockerContainerId . ';' . 'sudo docker rm ' . $dockerContainerId . ';';
            // exec($dockerRmCmd);

        // log::debug($request->cell_colors);
        // log::debug($codeExeCmdOutput);
        // log::debug($codeExeCmdStatus);



                // $testCmd =
        // // 'sudo docker start '. $dockerContainerId . ';' .
        // 'sudo docker exec '. $dockerContainerId . ' bash -c "cd /tmp && timeout 1 python code.py" 2>&1'
        // ;
        // exec($testCmd, $testCmdOutput, $testCmdStatus);
        
        // log::debug($dockerContainerId);
        // log::debug($testCmdOutput);
        // log::debug($testCmdStatus);



                    // $dockerCpCmd =
            // 'sudo docker exec '. $dockerContainerId . ' bash -c "cd /tmp && rm -Rf '. Auth::user()->name . ' && mkdir    ";'.
            // 'sudo docker cp ' . storage_path('app/' . Auth::user()->name . '/code ') . $dockerContainerId . ':/tmp/'. Auth::user()->name . '/code';