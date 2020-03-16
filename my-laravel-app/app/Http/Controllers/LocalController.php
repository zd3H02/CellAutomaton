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
            'cell_colors'       => explode(',', $localCell->cell_colors, config('CONST.LOCAL.MAX_CELL_NUM')),
            'MAX_CELL_ROW_NUM'  => config('CONST.LOCAL.MAX_CELL_ROW_NUM'),
            'MAX_CELL_COL_NUM'  => config('CONST.LOCAL.MAX_CELL_COL_NUM'),
            'MAX_CELL_NUM'      => config('CONST.LOCAL.MAX_CELL_NUM'),
        ];
        // log::debug($localCell->cell_code);
        return $param;
    }
    public function calc(Request $request)
    {
        $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();

        Storage::delete(Auth::user()->name . '/code/code.py');

        //chr(10)は改行文字
        $cellCodeWithAddCellColors = 'input_colors = ' . $request->cell_colors . chr(10) . $localCell->cell_code;
        // log::debug($cellCodeWithAddCellColors);
        Storage::put(Auth::user()->name . '/code/code.py', $cellCodeWithAddCellColors);

// log::debug($request->cell_colors);
        // $dockerRunCmd =
        //     'sudo docker create -i '.
        //     '--net none '.
        //     '--cpuset-cpus 0 '.
        //     '--memory 32m --memory-swap 32m '.
        //     '--ulimit nproc=1:1 '.
        //     '--ulimit fsize=1000000 '.
        //     'dockerworkspace_dev';
        // $dockerContainerId = exec($dockerRunCmd, $dockerRunCmdOutput, $dockerRunCmdStatus);

        $dockerGetConteinerIdCmd =
            'sudo docker ps -f ancestor=' . config('CONST.DOCKER_DEV_IMAGE_NAME') . ' -q';

        $dockerContainerId =
            exec(
                $dockerGetConteinerIdCmd,
                $dockerGetConteinerIdCmdOutput,
                $dockerGetConteinerIdCmdStatus
            );

        // log::debug($dockerGetConteinerIdCmd);
        // log::debug($dockerGetConteinerIdCmdOutput);
        // log::debug($dockerGetConteinerIdCmdStatus);


        $cdCmd = 'cd /tmp';
        $rmCmd = 'rm -Rf '. Auth::user()->name;
        $mkdirCmd = 'mkdir -p ' . Auth::user()->name . '/code';
        $dockerExecCdAndRmAndMkdirCmd =
            'sudo docker exec '. $dockerContainerId .
            ' bash -c "' . $cdCmd . ' && ' . $rmCmd .  ' && '. $mkdirCmd . '"';

        exec(
            $dockerExecCdAndRmAndMkdirCmd,
            $dockerExecCdAndRmAndMkdirCmdOutput,
            $dockerExecCdAndRmAndMkdirCmdStatus
        );

        // log::debug($dockerExecCdAndRmAndMkdirCmd);
        // log::debug($dockerExecCdAndRmAndMkdirCmdOutput);
        // log::debug($dockerExecCdAndRmAndMkdirCmdStatus);


        $storagePath = storage_path('app/' . Auth::user()->name . '/code/code.py ');
        $devConteinerPath = ':/tmp/'. Auth::user()->name . '/code';
        $dockerCpCmd = 'sudo docker cp ' . $storagePath . $dockerContainerId .$devConteinerPath;
        exec(
            $dockerCpCmd,
            $dockerCpCmdOutput,
            $dockerCpCmdStatus
        );

        // log::debug($dockerCpCmd);
        // log::debug($dockerCpCmdOutput);
        // log::debug($dockerCpCmdStatus);

        $codeExeCmd =
            'sudo docker exec '.
            $dockerContainerId .
            ' bash -c "cd /tmp/' .
            Auth::user()->name .
            '/code && timeout 1 python code.py" 2>&1';

        exec(
            $codeExeCmd,
            $codeExeCmdOutput,
            $codeExeCmdStatus
        );

        // log::debug($codeExeCmd);
        // log::debug($codeExeCmdOutput);
        // log::debug($codeExeCmdStatus);

        $tmpFormatedCodeExeCmdOutput_1 = str_replace('\'', '', $codeExeCmdOutput);
        $tmpFormatedCodeExeCmdOutput_2 = str_replace(' ', '', $tmpFormatedCodeExeCmdOutput_1);
        $tmpFormatedCodeExeCmdOutput_3 = str_replace('[', '', $tmpFormatedCodeExeCmdOutput_2);
        $tmpFormatedCodeExeCmdOutput_4 = str_replace(']', '', $tmpFormatedCodeExeCmdOutput_3);
        $clacCellColors = explode(',', implode($tmpFormatedCodeExeCmdOutput_4));
        // $clacCellColors = explode(',', implode($codeExeCmdOutput));
        $clacCellColorsCount = count($clacCellColors);
        $isCellColorsCountOk =  $clacCellColorsCount === config('CONST.LOCAL.MAX_CELL_NUM');
        if($isCellColorsCountOk) {
            $isCellColorsContentsOk = true;
            foreach($clacCellColors as $calcCellColor) {
                $isColorCode = preg_match('/^#[\da-fA-F]{6}$/', $calcCellColor);
                // log::debug($calcCellColor);
                if($isColorCode) {
                    //正常。何もしない。
                }
                else {
                    $isCellColorsContentsOk = false;
                    break;
                }
            }
        }
        else {
            $isCellColorsContentsOk = false;
        }

        $isUpdateCellColors = $isCellColorsCountOk && $isCellColorsContentsOk;
        if($isUpdateCellColors) {
            $sendCellColors = $clacCellColors;
        }
        else {
            $sendCellColors = json_decode($request->cell_colors);
        }

        // log::debug($clacCellColorsCount);
        // log::debug('$isCellColorsCountOk:' . $isCellColorsCountOk);
        // log::debug('$isCellColorsContentsOk:' . $isCellColorsContentsOk);
        // log::debug($isUpdateCellColors);
        // log::debug($sendCellColors);

        // $tmpCellColors =  rtrim(ltrim($request->cell_colors));
        // log::debug($sendCellColors);
        // log::debug($request->cell_colors);

        // $dummyCellColorsData = [];
        // for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
        //     $dummyCellColorsData[$i] =
        //          '#'
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
        //         ;
        // }

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
        // log::debug($localCell);
        // log::debug($request->cell_code);
        $localCell->cell_name = $request->cell_name;
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


































//             import math
// def get_neighborhood_sq_3x3(num):
//     global input_colors
//     MAX_COL_NUM = 20
//     MAX_ROW_NUM = 20
//     OUT_OF_RANGE = 'out_of_range'

//     center_col = num % MAX_COL_NUM
//     center_row = num / MAX_ROW_NUM

//     col_row_list = [
//         [center_col-1,center_row-1],[center_col+0,center_row-1],[center_col+1,center_row-1],
//         [center_col-1,center_row+0],[center_col+0,center_row+0],[center_col+1,center_row+0],
//         [center_col+1,center_row+1],[center_col+0,center_row+1],[center_col+1,center_row+1],
//     ] 
    
//     # print('row_list:' + str(col_row_list))
//     index_list = []
//     for col_row in col_row_list:
//         col = col_row[0]
//         row = col_row[1]
        
//         if col < 0 or col >= MAX_COL_NUM:
//             index_list.append(OUT_OF_RANGE)
//         elif row < 0 or row >= MAX_ROW_NUM:
//             index_list.append(OUT_OF_RANGE)
//         else:
//             index = MAX_ROW_NUM * row + col
//             index_list.append(index)
//     # print('index_list:' + str(index_list))

//     color_list = []
//     for index in index_list:
//         if index == OUT_OF_RANGE:
//             color_list.append('#000000')
//         else:
//             color_list.append(input_colors[index])
    
//     #print('color_list:' + str(color_list))
//     return color_list

// # print("tanu")
// # print(get_neighborhood_sq_3x3(5))

// LIFE_COLOR  = '#FFFFFF'
// DETH_COLOR = '#000000'
// new_colors = []
// for i, center_color in enumerate(input_colors):
//     deth_count = 0
//     life_count = 0
//     neighborhood = get_neighborhood_sq_3x3(i)
//     for color in neighborhood:
//         if color == DETH_COLOR:
//             deth_count += 1
//         else:
//             life_count += 1
            
//     # print("tanu")
//     # print(life_count)
//     # print("buta")
//     # print(deth_count)
//     # print("buta")
//     # print(neighborhood)
            
//     if center_color == DETH_COLOR:
//         if life_count == 3:
//             new_colors.append(LIFE_COLOR)
//         else:
//             new_colors.append(center_color)
//     else:
//         if life_count == 2 or life_count == 3:
//             new_colors.append(center_color)
//         elif life_count <= 1:
//             new_colors.append(DETH_COLOR)
//         elif life_count >= 4:
//             new_colors.append(DETH_COLOR)

// #print(life_count)
// #print(deth_count)
// print(new_colors)