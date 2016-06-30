<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sitesetting extends Model
{
	public $timestamps = false;
    protected $fillable=[
        'name',
        'display_name',
        'value',
        'code'
       ];
}
