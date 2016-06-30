<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductPostmasterShipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_postmaster_shipment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('shipment_package_id');
            $table->float('weight');
            $table->enum('weight_unit', ['LB','OZ','KG','G'])->default('LB');
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
        Schema::drop('order_postmaster_shipment');
    }
}
