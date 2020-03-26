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
        $items = LocalCell::where('creator',Auth::user()->name)->where('is_moved_to_trash', false)->orderBy('created_at', 'desc')->get();

        if(isset($request->id)) {
            $detailDisplayItem = LocalCell::find($request->id);
        }
        else {
            $detailDisplayItem = LocalCell::where('creator',Auth::user()->name)->where('is_moved_to_trash', false)->orderBy('created_at', 'desc')->first();
        }
        return view('home', compact('items', 'detailDisplayItem'));
    }
    public function del(Request $request)
    {
        $localCell = LocalCell::find($request->id);
        $localCell->is_moved_to_trash = true;
        $localCell->save();

        return redirect('home');
    }
    public function create(Request $request)
    {

        $localCell              = new LocalCell;
        $localCell->creator     = Auth::user()->name;
        $localCell->cell_name   = 'test';
        $localCell->cell_code   = <<<EOD
import math

MAX_COL_NUM = 20
MAX_ROW_NUM = 20
LIFE_COLOR = '#000000'
DETH_COLOR = '#ffffff'

def get_neighborhood_sq_3x3(num):
    global input_colors
    OUT_OF_RANGE = 'out_of_range'

    center_col = num % MAX_COL_NUM
    center_row = num / MAX_COL_NUM

    col_row_list = [
        [center_col-1,center_row-1],[center_col+0,center_row-1],[center_col+1,center_row-1],
        [center_col-1,center_row+0],[center_col+0,center_row+0],[center_col+1,center_row+0],
        [center_col-1,center_row+1],[center_col+0,center_row+1],[center_col+1,center_row+1],
    ]

    index_list = []
    for col_row in col_row_list:
        col = col_row[0]
        row = col_row[1]

        if col < 0 or col >= MAX_COL_NUM:
            index_list.append(OUT_OF_RANGE)
        elif row < 0 or row >= MAX_ROW_NUM:
            index_list.append(OUT_OF_RANGE)
        else:
            index = MAX_ROW_NUM * row + col
            index_list.append(index)

    color_list = []
    for index in index_list:
        if index == OUT_OF_RANGE:
            color_list.append(DETH_COLOR)
        else:
            color_list.append(input_colors[index])

    return color_list

new_colors = []
for i, center_color in enumerate(input_colors):
    deth_count = 0
    life_count = 0
    neighborhood = get_neighborhood_sq_3x3(i)
    for color in neighborhood:
        if color == DETH_COLOR:
            deth_count += 1
        else:
            life_count += 1

    if center_color == DETH_COLOR:
        if life_count == 3:
            new_colors.append(LIFE_COLOR)
        else:
            new_colors.append(center_color)
    else:
        life_count -= 1
        if life_count == 2 or life_count == 3:
            new_colors.append(center_color)
        elif life_count <= 1:
            new_colors.append(DETH_COLOR)
        elif life_count >= 4:
            new_colors.append(DETH_COLOR)

print(new_colors)
EOD;
        $localCell->cell_memo   = '';
        $localCell->cell_colors = config('CONST.LOCAL.INIT_CELL_COLORS');
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
    public function trashcan(Request $request)
    {
        $isTrash = true;
        $items = LocalCell::where('creator',Auth::user()->name)->where('is_moved_to_trash', true)->orderBy('created_at', 'desc')->get();
        if(isset($request->id)) {
            $detailDisplayItem = LocalCell::find($request->id);
        }
        else {
            $detailDisplayItem = LocalCell::where('creator',Auth::user()->name)->where('is_moved_to_trash', true)->orderBy('created_at', 'desc')->first();
        }

        return view('home', compact('isTrash', 'items', 'detailDisplayItem'));
    }
    public function forcedel(Request $request)
    {
        $localCell = LocalCell::find($request->id);
        Storage::delete(config('CONST.DELETE_FOLDER_PATH') . $localCell->detail_filename);
        Storage::delete(config('CONST.DELETE_FOLDER_PATH') . $localCell->thumbnail_filename);
        $localCell->Delete();

        return redirect('home/trashcan');
    }
    public function restore(Request $request)
    {
        $localCell = LocalCell::find($request->id);
        $localCell->is_moved_to_trash = false;
        $localCell->save();

        return redirect('home/trashcan');
    }
}