<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string("Name");
            $table->string("Address");
            $table->string("PostCode");
            $table->string("Logo")->nullable();
            $table->unsignedBigInteger("OwnerID")->nullable();
            $table->string("Key_ID")->nullable();
            $table->date("expiry_date")->nullable();
            $table->integer("totalTables")->nullable();
            $table->string("Status")->nullable();
            $table->string("Layout")->nullable();
            $table->boolean("enable_question");
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
        Schema::dropIfExists('restaurants');
    }
}
