<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	public $timestamps = false;
    

    protected $guarded = array();  // Important

    protected $table = 'subscription_history';
    
    public function getSubMembers(){
    	return $this->belongsTo('App\Model\Brandmember','member_id');
    }

   
}