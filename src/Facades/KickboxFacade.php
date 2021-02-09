<?php


namespace Juanparati\LaravelKickbox\Facades;

use Illuminate\Support\Facades\Facade;
use Juanparati\LaravelKickbox\Kickbox;


/**
 * Class KickboxFacade.
 *
 * @package Juanparati\LaravelKickbox\Facades
 */
class KickboxFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Kickbox::class;
    }

}