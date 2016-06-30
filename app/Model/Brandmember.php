<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brandmember extends Model
{
	//public $timestamps = false;
  /*  protected $fillable=[
        'fname',
        'lname',
        'username',
        'email',
        'password',
        'gender',
        'dob',
        'brand_sitelink',
        'brand_details',
        'phone_no',
        'role',
        'status',
        'admin_status',
        'business_name',
        'address',
        'slug',
        'pro_image',
        'government_issue',
        'bank_name',
        'routing_number',
        'account_number',
        'mailing_name',
        'mailing_address',
        'mailing_country_id',
        'mailing_city',
        'mailing_lastname',
        'mailing_address2',
        'mailing_state',
        'mailing_postcode',
        'card_details',
        'call_datetime',
        'paypal_email',
        'mailing_address',
        'default_band_preference',
        'brand_type',
        'business_doc',
        'auth_profile_id',
        'auth_payment_profile_id',
        'auth_address_id',
        'youtube_link',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'updated_at',
        'created_at'
       ];*/
    
    public $timestamps = true;
    

    protected $guarded = array();  // Important

    protected $table = 'brandmembers';
}
