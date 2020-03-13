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
            'cell_color'        => explode(',', $localCell->cell_color, config('CONST.LOCAL.MAX_CELL_NUM')),
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

        $dummyCellColorData = [];
        for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
            $dummyCellColorData[$i] =
                 '#'
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                ;
        }


        $localCell =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        

        $dockerRunCmd = 'sudo docker create -i dockerworkspace_dev';
        $dockerContainerId = exec($dockerRunCmd, $dockerRunCmdOutput, $dockerRunCmdStatus);

        // $dockerContainerId = '2ee960f89c40';
        // log::debug(str_replace('"', '\"', $localCell->cell_code));
        $codeExeCmd =
            'sudo docker start -i'. $dockerContainerId . ";"
            // 'sudo docker exec -i ' . $dockerContainerId . ' ls';
            // .'sudo docker exec -i '. $dockerContainerId .' sh -c "'
            .'sh -c "'
            .'cd /tmp;'
            .'rm work.py;'
            .'touch work.py;'
            // // .'echo "input_cell_color = ' . $request->cell_color . '.split(",")"'
            // // .'echo "' . $localCell->cell_code . '" >> work.py;'
            // // .'echo "print(",".join(output_cell_color))"'
            // . 'echo \"print(12345)\" >> work.py;'
            . 'echo \"' . str_replace('"', '\'', $localCell->cell_code) . '\" >> work.py;'
            . 'timeout 1 python work.py;'
            .'" 2>&1';

        $cellColor = exec($codeExeCmd, $codeExeCmdOutput, $codeExeCmdStatus);

        $dockerRmCmd = 'sudo docker stop ' . $dockerContainerId . ';' . 'sudo docker rm ' . $dockerContainerId . ';';
        exec($dockerRmCmd);

        log::debug($cellColor);
        log::debug($codeExeCmdOutput);
        log::debug($codeExeCmdStatus);

        return $dummyCellColorData;
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

        // $requestCellCollor = explode(',', $request->cell_color);

        MyFunc::createCellColorJpg(
            $thumbnailFileName,
            config('CONST.LOCAL.THUMBNAIL_HEIGHT'),
            config('CONST.LOCAL.THUMBNAIL_WIDTH'),
            $request->cell_color
        );
        MyFunc::createCellColorJpg(
            $detailsFileName,
            config('CONST.LOCAL.DETAILS_HEIGHT'),
            config('CONST.LOCAL.DETAILS_WIDTH'),
            $request->cell_color
        );


        $localCell = LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        // log::debug($request->cell_color);
        $localCell->cell_color = $request->cell_color;
        $localCell->thumbnail_filename = $thumbnailFileName;
        $localCell->detail_filename    = $detailsFileName;
        $localCell->save();

        return ["cellColorSaveSuccess"];
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