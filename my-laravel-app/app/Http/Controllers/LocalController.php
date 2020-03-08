<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function index(Request $request)
    {

        return view('local');
    }
    public function get(Request $request)
    {
        return $num = $request->num + 1;
        // return view('local', compact('num'));
    }
    public function run(Request $request)
    {
        $test1 = exec('sudo docker ps', $test2, $test3);
        var_dump($test1);
        var_dump($test2);
        var_dump($test3);
        return view('local',compact('test1','test2','test3'));
    }
    public function stop(Request $request)
    {
        // $dockerCmd =
        // 'sudo docker create'

        $test1 = exec("sudo docker exec -i 804028b02ec5 python tmp/hello.py", $test2, $test3);

        //log::debug($test2);
        // return $num = $request->num + 1;
        return $num = $test2;
    }
    public function save(Request $request)
    {
        return ["tanuki"];
    }
}
