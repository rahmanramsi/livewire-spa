<?php

namespace Rahmanramsi\LivewireSpa\Http\Livewire;

use Throwable;
use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\ImplicitRouteBinding;
use Rahmanramsi\LivewireSpa\Facades\LivewireSpa;
use Rahmanramsi\LivewireSpa\Http\Livewire\Pages\ErrorPage;

class SinglePage extends Component
{
    public $currentUrl;
    public $updateAddressBar = false;

    protected $listeners = ['update-url' => 'setUrl'];

    public function mount()
    {
        $this->currentUrl = url()->current();
    }

    public function render()
    {
        try {
            $parameters = $this->getParameters();
            $livewireAlias = $this->getAlias();
        } catch (Throwable $e) {
            $parameters = [];
            $livewireAlias = Livewire::getAlias(config('livewire-spa.pages.error', ErrorPage::class));
        }

        $layout = $this->getPageClass()::getLayout();

        return view($layout, [
            'parameters' => $parameters,
            'alias' => $livewireAlias,
        ])->layout('livewire-spa::layout.blank');
    }

    protected function getRoute()
    {
        try {
            return app('router')->getRoutes()->match(request()->create($this->currentUrl));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    protected function getPageClass()
    {
        try {
            $uri = Str::replaceFirst(config('livewire-spa.path'), '', $this->getRoute()?->uri);
            if ($uri == '/' || $uri == '') {
                return config('livewire-spa.pages.default');
            }

            return LivewireSpa::getPageClass($uri);
        } catch (Throwable $e) {
            return config('livewire-spa.pages.error', ErrorPage::class);
        }
    }

    protected function getAlias()
    {
        return Livewire::getAlias($this->getPageClass());
    }

    public function getParameters()
    {
        $class = $this->getPageClass();

        try {
            return (new ImplicitRouteBinding(app()))->resolveMountParameters($this->getRoute(), new $class())->all();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function setUrl($url, $updateAddressBar = false)
    {
        $this->currentUrl = $url;
        $this->updateAddressBar = $updateAddressBar;
    }
}
