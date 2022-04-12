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
            $table->float("amount")->nullable();
            $table->float("table_number")->nullable();
            $table->date("date")->nullable();
            $table->unsignedBigInteger("restaurant_id")->nullable();
            $table->string("status")->nullable();
            $table->float("discount")->nullable();
            $table->string("payment_method")->nullable();
            $table->string("remarks")->nullable();
            $table->string("type")->nullable();
            $table->string("autoprint")->nullable()->default(false);
            $table->string("customer_name")->nullable();
            $table->unsignedBigInteger("order_by")->nullable();
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->float("cash")->nullable();
            $table->float("card")->nullable();

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
