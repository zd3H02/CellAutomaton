<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LocalCell;

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
        $items = LocalCell::all();
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
        $localCell = new LocalCell;
        $localCell->creator     = $request->creator;
        $localCell->cell_name   = "test";
        $localCell->save();
        return redirect('home');
    }
}
