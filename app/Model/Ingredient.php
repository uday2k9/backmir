<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
	public $timestamps = false;
	protected $fillable = array('name', 'description', 'chemical_name','price_per_gram','list_manufacture','type','organic','antibiotic_free','gmo','image','status','weight_measurement');
    protected $table = "ingredients";

    public function ingredientFormfactor()
    {
        return $this->hasMany('App\Model\IngredientFormfactor');
    }
}
