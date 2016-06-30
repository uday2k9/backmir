<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductFormfactor extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'product_formfactors';

  	public function GetFormFactorDetails()
    {
        return $this->belongsTo('App\Model\FormFactor','formfactor_id');
    }
    
     public function formfactorProducts()
    {
        return $this->belongsTo('App\Model\ProductFormfactor','product_id');
    }
}
