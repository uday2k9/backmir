<?php
return [ 

    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */

    /**
     * Storage
     */
    'storage' => 'Session', 

    /**
     * Consumers
     */
    'consumers' => [

        /**
         * Facebook
         */
        'Facebook' => [
            'client_id'     => '',
            'client_secret' => '',
            'scope'         => [],
        ],
        'Google' => [
            'client_id'     => 'Your Google client ID',
            'client_secret' => 'Your Google Client Secret',
            'scope'         => ['userinfo_email', 'userinfo_profile'],
        ],  

    ]

];