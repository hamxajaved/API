<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('avatar')->nullable();
            $table->string('stock')->nullable();
            $table->unsignedBigInteger('plant_categories_id');
            $table->foreign('plant_categories_id')->references('id')->on('plantcategories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('plant_type_id');
            $table->foreign('plant_type_id')->references('id')->on('planttypes')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plants');
    }
}
