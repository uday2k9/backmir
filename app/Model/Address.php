<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
     protected $guarded = array();  // Important
}
