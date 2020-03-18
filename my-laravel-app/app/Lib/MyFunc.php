<?php

namespace App\Lib;
use Illuminate\Support\Facades\Storage;

class MyFunc
{
    public static function createCellColorsJpg($fileName, $height, $width, $cellColors){
        Storage::delete(config('CONST.SAVE_FOLDER_PATH') . $fileName);
        $fillRectX = $height / config('CONST.LOCAL.MAX_CELL_ROW_NUM');
        $fillRextY = $width  / config('CONST.LOCAL.MAX_CELL_COL_NUM');

        $arrayedCellColors = explode(',', $cellColors);

        $imgResource = imagecreatetruecolor($height, $width);
        foreach($arrayedCellColors as $i => $color) {
            $col = intval($i % config('CONST.LOCAL.MAX_CELL_COL_NUM'));
            $row = intval($i / config('CONST.LOCAL.MAX_CELL_COL_NUM'));
            $beginX = ceil($fillRectX * $col);
            $beginY = ceil($fillRextY * $row);
            $endX   = ceil($beginX + $fillRectX);
            $endY   = ceil($beginY + $fillRextY);
            //$colorは＃000000~#FFFFFF
            $colorR = hexdec(substr($color, 1, 2));
            $colorG = hexdec(substr($color, 3, 2));
            $colorB = hexdec(substr($color, 5, 2));
            $fillColor = imagecolorallocate($imgResource, $colorR, $colorG, $colorB);
            imagefilledrectangle(
                $imgResource
                , $beginX
                , $beginY
                , $endX
                , $endY
                , $fillColor
            );
        }
        imagejpeg($imgResource, storage_path(config('CONST.SAVE_FOLDER_PATH') . $fileName));
        imagedestroy($imgResource);
    }
}



