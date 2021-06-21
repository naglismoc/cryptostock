<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("action");
            $table->unsignedBigInteger('currency');
            $table->foreign('currency')->references('id')->on('stocks');
            $table->double("price");
            $table->double("quantity");
            $table->double("quantity_left");
            $table->double("trigger_price");
            $table->integer("status");
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stock_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stock_id')->references('id')->on('stocks');
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
        Schema::dropIfExists('orders');
    }
}
