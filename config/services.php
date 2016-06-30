<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    //     'facebook' => [
    //     'client_id' => '1712231159008324',
    //     'client_secret' => 'cfaefc82fe22a30a071960c46d09362f',
    //     'redirect' => 'http://www.miramix-development.com/account/facebook',
    // ],
    //    'google' => [
    //     'client_id'     => '649990483092-gsof9f6vtg5v1kg95mkknle69ce83s9i.apps.googleusercontent.com',
    //     'client_secret' => 'K3-YyyM7e-71xezXj_lqCknr',
    //     'redirect'      => 'http://www.miramix-development.com/account/google'
    // ],

   /*'facebook' => [
        'client_id' => '1712231159008324',
        'client_secret' => 'cfaefc82fe22a30a071960c46d09362f',
        'redirect' => 'http://www.miramix.com/account/facebook',
    ],
       'google' => [
        'client_id'     => '649990483092-gsof9f6vtg5v1kg95mkknle69ce83s9i.apps.googleusercontent.com',
        'client_secret' => 'K3-YyyM7e-71xezXj_lqCknr',
        'redirect'      => 'http://www.miramix.com/account/google'
    ],*/
 'facebook' => [
        'client_id' => env('FB_CLIENT_ID'),
        'client_secret' => env('FB_CLIENT_SECRET'),
        'redirect' => 'http://www.miramix.com/account/facebook',
    ],
       'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => 'http://www.miramix.com/account/google'
    ],  
   
/*
     'facebook' => [
        'client_id' => '907683882655002',
        'client_secret' => 'bbb1db05b247729952ad4c968647e36f',
        'redirect' => 'http://www.miramix.com/account/facebook',
    ],
       'google' => [
        'client_id'     => '125667655280-r088iljh6jt7d6m2eilkri5rdm8d0hpu.apps.googleusercontent.com',
        'client_secret' => 'kcWS2DXZI7efSp6X0x64oKlk',
        'redirect'      => 'http://www.miramix.com/account/google'
    ],
*/
    'twilio' => [
    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'from' => env('TWILIO_FROM_NUMBER'),
    'ssl_verify' => false, // Development switch to bypass API SSL certificate verfication
    ],

];
