<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShipmentPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('shipment_package', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type',['TUBE','LETTER','CARRIER_BOX_SMALL','CARRIER_BOX_MEDIUM','CARRIER_BOX_LARGE','CUSTOM'])->default('LETTER');
            $table->float('width');
            $table->float('height');
            $table->float('length');
            $table->enum('dimension_units', ['LB','OZ','KG','G'])->default('LB');
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
         Schema::drop('shipment_package');

    }
}
