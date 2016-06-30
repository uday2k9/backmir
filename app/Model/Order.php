<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	public $timestamps = false;
    protected $guarded = array();  // Important

    protected $table = 'orders';

    public function AllOrderItems()
    {
        return $this->hasMany('App\Model\OrderItems','order_id');
    }
    
    public function getOrderMembers(){
    	return $this->belongsTo('App\Model\Brandmember','user_id');
    }
}
