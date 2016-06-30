<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sitemessage extends Model
{
	public $timestamps = true;
    protected $fillable=[
        'msg_title',
        'slug',
        'subject',
        'sms_text',
        'email_text',
        'status'
       ];
}
