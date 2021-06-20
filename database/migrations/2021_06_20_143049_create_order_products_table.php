<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->unsignedBigInteger('plant_id');
            $table->foreign('plant_id')->references('id')->on('plants')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('quantity')->nullable();
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
        Schema::dropIfExists('order_products');
    }
}
