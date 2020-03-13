<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LocalCell;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MyFunc;

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
        return view('home', compact('items'));
    }
    public function del(Request $request)
    {
        $localCell = LocalCell::find($request->id);
        ;
        Storage::delete(config('CONST.DELETE_FOLDER_PATH') . $localCell->detail_filename);
        Storage::delete(config('CONST.DELETE_FOLDER_PATH') . $localCell->thumbnail_filename);
        $localCell->delete();

        return redirect('home');
    }
    public function post(Request $request)
    {

        $localCell             = new LocalCell;
        $localCell->creator    = Auth::user()->name;
        $localCell->cell_name  = 'test';
        $localCell->cell_code  = '';
        $localCell->cell_color = config('CONST.LOCAL.INIT_CELL_COLOR');
        $localCell->publish    = false;

        $localCell->save();

        $thumbnailFileName    = 'thumbnail_'  . Auth::user()->name . '_'. $localCell->id . '.jpg';
        $detailsFileName      = 'details_'    . Auth::user()->name . '_'. $localCell->id . '.jpg';

        MyFunc::createCellColorJpg(
            $thumbnailFileName,
            config('CONST.LOCAL.THUMBNAIL_HEIGHT'),
            config('CONST.LOCAL.THUMBNAIL_WIDTH'),
            config('CONST.LOCAL.INIT_CELL_COLOR')
        );
        MyFunc::createCellColorJpg(
            $detailsFileName,
            config('CONST.LOCAL.DETAILS_HEIGHT'),
            config('CONST.LOCAL.DETAILS_WIDTH'),
            config('CONST.LOCAL.INIT_CELL_COLOR')
        );

        $localCell->thumbnail_filename  = $thumbnailFileName;
        $localCell->detail_filename     = $detailsFileName;

        $localCell->save();

        return redirect('home');
    }
}
