<?php

namespace UnexpectedJourney\FilamentToolbox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \UnexpectedJourney\FilamentToolbox\FilamentToolbox
 */
class FilamentToolbox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \UnexpectedJourney\FilamentToolbox\FilamentToolbox::class;
    }
}
