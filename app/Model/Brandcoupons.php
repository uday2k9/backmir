<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brandcoupons extends Model
{

    public $timestamps = false;
    protected $guarded = array();  // Important
    protected $table = 'brand_coupons';
}