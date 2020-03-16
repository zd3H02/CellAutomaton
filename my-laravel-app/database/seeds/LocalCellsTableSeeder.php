<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalCellsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tmpDummyCellColors = [];
        for ($i = 0; $i < config('CONST.LOCAL.MAX_CELL_NUM'); $i++) {
            $tmpDummyCellColors[$i] = 
                 '#'
                .str_pad(dechex(mt_rand(0, 255)),2,'0',STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),2,'0',STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),2,'0',STR_PAD_LEFT)
                ;
        }
        $dummyCellColors = implode( ",",$tmpDummyCellColors);
        $param = [
            'creator'       => 'zd3H02',
            'cell_name'     => 'tanuki',
            'cell_code'     => 'test',
            'cell_colors'    => $dummyCellColors,
            'created_at'    => now(),
        ];
        DB::table('local_cells')->insert($param);
    }
}
