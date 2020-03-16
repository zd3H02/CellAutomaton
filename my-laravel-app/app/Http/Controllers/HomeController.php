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
    public function index(Request $request)
    {
        $items = LocalCell::where('creator',Auth::user()->name)->orderBy('created_at', 'desc')->get();

        if(isset($request->id)) {
            $detailDisplayItem = LocalCell::find($request->id);
        }
        else {
            $detailDisplayItem = LocalCell::where('creator',Auth::user()->name)->orderBy('created_at', 'desc')->first();
        }

        return view('home', compact('items', 'detailDisplayItem'));
    }
    public function del(Request $request)
    {
        $localCell = LocalCell::find($request->id);
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
        $localCell->cell_colors = config('CONST.LOCAL.INIT_CELL_COLORS');
        $localCell->publish    = false;

        $localCell->save();

        $thumbnailFileName    = 'thumbnail_'  . Auth::user()->name . '_'. $localCell->id . '.jpg';
        $detailsFileName      = 'details_'    . Auth::user()->name . '_'. $localCell->id . '.jpg';

        MyFunc::createCellColorsJpg(
            $thumbnailFileName,
            config('CONST.LOCAL.THUMBNAIL_HEIGHT'),
            config('CONST.LOCAL.THUMBNAIL_WIDTH'),
            config('CONST.LOCAL.INIT_CELL_COLORS')
        );
        MyFunc::createCellColorsJpg(
            $detailsFileName,
            config('CONST.LOCAL.DETAILS_HEIGHT'),
            config('CONST.LOCAL.DETAILS_WIDTH'),
            config('CONST.LOCAL.INIT_CELL_COLORS')
        );

        $localCell->thumbnail_filename  = $thumbnailFileName;
        $localCell->detail_filename     = $detailsFileName;

        $localCell->save();

        return redirect('home');
    }
}
