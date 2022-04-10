<?php

namespace Itoufo\Notifer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Notifer.
 */
class Notifer extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'notifer';
    }
}
