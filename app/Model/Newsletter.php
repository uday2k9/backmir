<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
	public $timestamps = false;
    

    protected $guarded = array();  // Important

    protected $table = 'newsletter_subscription';
    
   

   
}