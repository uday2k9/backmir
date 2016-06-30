<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
	public $timestamps = false;
   

    protected $guarded = array();  // Important

    protected $table = 'product_rating';
    
    public function getRatings(){
    	return $this->belongsTo('App\Model\Product','product_id');
    }
    
    public function getMembers(){
    	return $this->belongsTo('App\Model\Brandmember','user_id');
    }


}
