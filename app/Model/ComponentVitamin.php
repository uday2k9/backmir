<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComponentVitamin extends Model
{
	protected $fillable = array('component_id', 'vitamin', 'weight','vitamin_weight');
   
    public $timestamps = false;
}
