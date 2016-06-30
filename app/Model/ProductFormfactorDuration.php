<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductFormfactorDuration extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'product_formfactor_durations';

  	public function GetFormFactorDurationDetails()
    {
        return $this->belongsTo('App\Model\ProductFormfactor','product_formfactor_id');
    }
    
}