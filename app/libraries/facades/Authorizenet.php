<?php namespace App\Libraries\Facades;

use Illuminate\Support\Facades\Facade;

class Authorizenet extends Facade {

    protected static function getFacadeAccessor()
    {
        return new \App\Libraries\Authorizenet\Authorizenet;
    }

}