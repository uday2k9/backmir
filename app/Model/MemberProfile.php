<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
	public $timestamps = false;
   

    protected $guarded = array();  // Important

    protected $table = 'member_profiles';

   


}
