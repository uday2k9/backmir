<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShipmentPackage extends Model
{
    //
    public $timestamps = false;
    protected $table = 'shipment_package';
    protected $fillable=[
        'name',
        'type',
        'width',
        'height',
        'length',
        'dimension_units',
        'p_type',
       ];
}
