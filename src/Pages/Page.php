<?php

namespace Rahmanramsi\LivewireSpa\Pages;

use Livewire\Component;
use Illuminate\Support\Str;

abstract class Page extends Component
{
    protected static string $route;

    protected static ?string $slug = null;

    public static function getRoute(): string
    {
        return static::$route;
    }

    public static function getRouteName(): string
    {
        $slug = static::getSlug();

        return "livewire-spa.{$slug}";
    }

    public static function getSlug(): string
    {
        if ($slug = static::$slug) {
            return $slug;
        }

        $slug = Str::startsWith(static::class, 'App\\LivewireSpa\\')
            ? Str::of(Str::after(static::class, 'App\\LivewireSpa\\'))
            : Str::of(class_basename(static::class));

        return $slug->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');
    }
}
