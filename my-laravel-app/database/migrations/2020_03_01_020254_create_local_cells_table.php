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
            $table->string('cell_code')->nullable();
            $table->text('cell_color_data');
            $table->string('thumbnail_image_path')->nullable();
            $table->string('detail_image_path')->nullable();
            $table->boolean('publish')->default(false);
            $table->softDeletes();
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
