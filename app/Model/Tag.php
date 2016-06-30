<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    public $timestamps = false;
    

    protected $guarded = array();  // Important

    protected $table = 'tags';
    
    public function gettags(){
    	return $this->belongsTo('App\Model\Product','product_id');
    }
}
