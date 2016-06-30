<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscriptionhistory extends Model
{
	public $timestamps = false;
    

    protected $guarded = array();  // Important

    protected $table = 'subscription_history_archive';
    
    public function getSubMembers(){
    	return $this->belongsTo('App\Model\Brandmember','member_id');
    }

   
}