<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IngredientFormfactor extends Model
{
	protected $fillable = array('ingredient_id', 'form_factor_id');
   
    public $timestamps = false;

    protected $table = 'ingredient_formfactors';

    public function formfactor()
    {
        return $this->belongsTo('App\Model\FormFactor');
    }

    public function ingredient()
    {
        return $this->belongsTo('App\Model\Ingredient');
    }

}
