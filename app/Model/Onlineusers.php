<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Onlineusers extends Model
{
	public $timestamps = false;
    

    protected $guarded = array();  // Important

    protected $table = 'user_online';
    
   

   
}