<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("price");
            $table->unsignedBigInteger("restaurant_id")->nullable();
            $table->unsignedBigInteger("menu_id")->nullable();
            $table->string("image")->nullable();
            $table->string("description")->nullable();
            $table->integer("take_away")->nullable();
            $table->integer("delivery")->nullable();
            $table->string("type")->nullable();
            $table->string("ingredients")->nullable();
            $table->string("calories")->nullable();
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
        Schema::dropIfExists('dishes');
    }
}
