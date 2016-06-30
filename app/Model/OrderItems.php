<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'order_items';

     
    public function order(){
    	return $this->belongsTo('App\Model\Order');
    }
}
