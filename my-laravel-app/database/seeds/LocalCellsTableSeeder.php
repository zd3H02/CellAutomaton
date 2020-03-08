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
        $tmpDummyCellColorData = [];
        for ($i=0; $i<100; $i++) {
            $tmpDummyCellColorData[$i] = 
                 '#'
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                .str_pad(dechex(mt_rand(0, 255)),0,2,STR_PAD_LEFT)
                ;
        }
        $dummyCellColorData = implode( ",",$tmpDummyCellColorData);
        $param = [
            'creator'           => 'zd3H02',
            'cell_name'         => 'tanuki',
            'cell_code'         => 'test',
            'cell_color_data'   => $dummyCellColorData,
            'created_at'        => now(),
        ];
        DB::table('local_cells')->insert($param);
    }
}
