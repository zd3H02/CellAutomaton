<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorldCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('world_cells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('x');
            $table->bigInteger('y');
            $table->bigInteger('color');
            $table->unsignedInteger('local_cell_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('world_cells');
    }
}
