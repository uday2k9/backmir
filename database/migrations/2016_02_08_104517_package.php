<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Package extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('package', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');           
            $table->string('formfactor');
            $table->string('brandmember');
            $table->string('package_type');
            $table->integer('maximum_width');
            $table->integer('maximum_height');
            $table->integer('maximum_depth');           
            $table->integer('minimum_unit');
            $table->integer('maximum_unit');
            $table->integer('minimum_bound_label');
            $table->integer('maximum_bound_label');
            $table->tinyInteger('status')->default('1');
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
        //
        Schema::drop('package');
    }
}
