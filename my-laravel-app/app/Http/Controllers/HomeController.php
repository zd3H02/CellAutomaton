<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LocalCell;
use Auth;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $items = LocalCell::where('creator',Auth::user()->name)->get();
        //dd($items);
        return view('home', compact('items'));
    }
    public function del(Request $request)
    {
        $localCell = LocalCell::find($request->id);
        $localCell->delete();
        return redirect('home');

    }
    public function post(Request $request)
    {
        log::debug(config('CONST.LOCAL.INIT_CELL_COLOR_DATA'));

        $localCell                  = new LocalCell;
        $localCell->creator         = Auth::user()->name;
        $localCell->cell_name       = "test";
        $localCell->cell_code       = "";
        $localCell->cell_color_data = config('CONST.LOCAL.INIT_CELL_COLOR_DATA');
        $localCell->save();
        return redirect('home');
    }
}
