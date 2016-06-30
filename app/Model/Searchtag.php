<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Searchtag extends Model
{
	public $timestamps = false;
   

    protected $guarded = array();  // Important

    protected $table = 'searchtags';

    // public function AllProductFormfactors()
    // {
    //     return $this->hasMany('App\Model\ProductFormfactor');
    // }

    // public function GetBrandDetails()
    // {
    //     return $this->belongsTo('App\Model\Brandmember','brandmember_id');
    // }


}
