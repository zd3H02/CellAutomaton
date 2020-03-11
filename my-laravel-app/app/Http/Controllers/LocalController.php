<?php

namespace App\Http\Controllers;
use App\LocalCell;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        return view('local',compact('id'));
    }
    public function first(Request $request)
    {
        $item =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        $param = [
            'cell_name'         => $item->cell_name,
            'cell_code'         => $item->cell_code,
            'cell_color_data'   => explode(',', $item->cell_color_data, config('CONST.LOCAL.MAX_CELL_NUM')),
            'MAX_CELL_ROW_NUM'  => config('CONST.LOCAL.MAX_CELL_ROW_NUM'),
            'MAX_CELL_COL_NUM'  => config('CONST.LOCAL.MAX_CELL_COL_NUM'),
            'MAX_CELL_NUM'      => config('CONST.LOCAL.MAX_CELL_NUM'),
        ];
        log::debug(config('CONST.LOCAL.MAX_CELL_NUM'));
        return $param;
    }
    public function calc(Request $request)
    {
        exec("sudo docker exec -i 804028b02ec5 python tmp/hello.py", $output, $status);

        $dummyCellColorData = [];
        for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
            $dummyCellColorData[$i] =
                 '#'
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                ;
        }
        // return $output;
        return $dummyCellColorData;
    }
    public function codesave(Request $request)
    {
        $item =  LocalCell::where('creator', Auth::user()->name)->where('id', $request->id)->first();
        // log::debug($item);
        // log::debug($request->cell_code);
        $item->cell_code = $request->cell_code;
        $item->save();
        $cmd = 
            'sudo docker exec -i 804028b02ec5 sh -c "'
            .'cd /tmp;'
            .'rm work.py;'
            .'touch work.py;'
            .'echo \"' . $item->cell_code . '\" >> work.py;'
            . 'python work.py;'
            .'"';

        $test1 = exec($cmd,$test2, $test3);
        log::debug($cmd);
        log::debug($test2);
        return ["cellCodeSaveSuccess"];
    }
    public function cellcolorsave(Request $request)
    {
        log::debug(gd_info ());
        // imagecreatetruecolor ( int $width , int $height )
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