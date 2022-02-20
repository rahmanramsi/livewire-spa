<?php

namespace Rahmanramsi\LivewireSpa;

use Illuminate\Support\Str;

class LivewireSpaManager
{
    protected array $pages = [];
    protected bool $isRouteMounted = false;

    public function getPages()
    {
        return array_unique($this->pages);
    }

    public function registerPages(array $pages): void
    {
        $this->pages = array_merge($this->pages, $pages);
    }

    public function getPageClass($uri)
    {

        $key = collect($this->getPages())
            ->search(fn ($page, $key) => Str::start($page::getRoute(), '/') === Str::start($uri, '/'));
        
        return $key !== false ? $this->getPages()[$key] : null;
    }
}
