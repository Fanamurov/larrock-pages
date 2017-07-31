<?php

namespace Larrock\ComponentPages\Facades;

use Illuminate\Support\Facades\Facade;

class LarrockPages extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larrockpages';
    }

}