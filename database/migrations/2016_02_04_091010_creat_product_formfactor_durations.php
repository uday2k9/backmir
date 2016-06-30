<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatProductFormfactorDurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creating the table
        Schema::create('product_formfactor_durations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_formfactor_id');
            $table->integer('duration');
            $table->double('min_price', 12, 2);
            $table->double('recommended_price', 12, 2);
            $table->double('actual_price', 12, 2);           
            $table->foreign('product_formfactor_id')->references('id')->on('product_formfactors');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropppig the table
        Schema::drop('flights');
    }
}
