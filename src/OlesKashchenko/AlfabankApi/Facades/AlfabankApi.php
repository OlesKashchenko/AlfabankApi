<?php

namespace OlesKashchenko\AlfabankApi\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class AlfabankApi extends Facade {
 
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    {
        return 'alfabankapi';
    } // end getFacadeAccessor
 
}