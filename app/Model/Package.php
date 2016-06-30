<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
	public $timestamps = false;
    // protected $fillable=[
    //     'name',
    //     'price',
    //     'maximum_weight',
    //     'minimum_weight'
    //    ];

    protected $guarded = array();  // Important

    protected $table = 'package';

   
	//public function ptype()
	//{
	//	return $this->belongsTo('App\PackageType');
	//}


}
