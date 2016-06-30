<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormFactor extends Model
{
	public $timestamps = false;
    protected $fillable=[
        'name',
        'price',
        'image',
        'maximum_weight',
        'minimum_weight'
       ];

    protected $table = 'form_factors';

    public function ingredientFormfactor()
    {
        return $this->hasMany('App\Model\IngredientFormfactor');
    }
}
