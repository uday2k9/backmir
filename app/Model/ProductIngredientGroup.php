<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductIngredientGroup extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'product_ingredient_group';

   
}
