<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Filemanagement extends Model
{
	public $timestamps = false;
    // protected $fillable=[
    //     'name',
    //     'price',
    //     'maximum_weight',
    //     'minimum_weight'
    //    ];

    protected $guarded = array();  // Important

    protected $table = 'file_management';

   
	//public function ptype()
	//{
	//	return $this->belongsTo('App\PackageType');
	//}


}
