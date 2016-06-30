<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
	public $timestamps = false;
    // protected $fillable=[
    //     'name',
    //     'price',
    //     'maximum_weight',
    //     'minimum_weight'
    //    ];

    //protected $guarded = array();  // Important

    protected $table = 'package_type';

   



}
