<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderPostmasterShipment extends Model
{
    //

    public $timestamps = true;
    

    protected $fillable = array('order_id', 'shipment_package_id', 'weight');

    protected $table = 'order_postmaster_shipment';
}
