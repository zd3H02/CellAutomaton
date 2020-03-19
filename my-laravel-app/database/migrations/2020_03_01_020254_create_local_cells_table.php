<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_cells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('creator');
            $table->string('cell_name')->nullable();
            $table->text('cell_code')->nullable();
            $table->text('cell_colors');
            $table->string('thumbnail_filename')->nullable();
            $table->string('detail_filename')->nullable();
            $table->boolean('is_publish')->default(false);
            $table->boolean('is_moved_to_trash')->default(false);
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
        Schema::dropIfExists('local_cells');
    }
}
