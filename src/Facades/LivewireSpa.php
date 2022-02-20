<?php

namespace Rahmanramsi\LivewireSpa\Facades;

use Illuminate\Support\Facades\Facade;

class LivewireSpa extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'livewire-spa';
    }
}
