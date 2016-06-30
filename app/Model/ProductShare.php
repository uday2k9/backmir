<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductShare extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'product_shares';
}
