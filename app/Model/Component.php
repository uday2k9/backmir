<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
	protected $fillable = array('component_name', 'percentage', 'ingredient_id');
   
    public $timestamps = false;
}
