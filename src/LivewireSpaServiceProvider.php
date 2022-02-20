<?php

namespace Rahmanramsi\LivewireSpa;

use ReflectionClass;
use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Rahmanramsi\LivewireSpa\Commands\MakeCommand;
use Rahmanramsi\LivewireSpa\Pages\Page;
use Rahmanramsi\LivewireSpa\Http\Livewire\SinglePage;
use Rahmanramsi\LivewireSpa\Facades\LivewireSpa;
use Spatie\LaravelPackageTools\Package;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewireSpaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-spa')
            ->hasViews()
            ->hasRoutes(['web'])
            ->hasConfigFile()
            ->hasCommand(MakeCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('livewire-spa', function (): LivewireSpaManager {
            return app(LivewireSpaManager::class);
        });

        $this->discoverPages();
    }

    public function packageBooted(): void
    {
        $this->bootLivewireComponents();
        $this->registerBladeDirectives();
    }

    protected function bootLivewireComponents(): void
    {
        Livewire::component('livewire-spa.core.single-page', SinglePage::class);
        Livewire::component('livewire-spa.core.error-page', ErrorPage::class);

        $this->registerLivewireComponentDirectory(config('livewire-spa.livewire.path'), config('livewire-spa.livewire.namespace'), 'livewire-spa.');
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('livewireSpaStyles', [LivewireSpaBladeDirectives::class, 'livewireSpaStyles']);
        Blade::directive('livewireSpaScripts', [LivewireSpaBladeDirectives::class, 'livewireSpaScripts']);
    }


    protected function discoverPages(): void
    {
        $filesystem = app(Filesystem::class);

        LivewireSpa::registerPages(array_merge(config('livewire-spa.pages.register', []), [config('livewire-spa.pages.error')]));
        if (!$filesystem->exists(config('livewire-spa.pages.path'))) {
            return;
        }

        LivewireSpa::registerPages(collect($filesystem->allFiles(config('livewire-spa.pages.path')))
            ->map(function (SplFileInfo $file): string {
                return (string) Str::of(config('livewire-spa.pages.namespace'))
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn (string $class): bool => is_subclass_of($class, Page::class) && (!(new ReflectionClass($class))->isAbstract()))
            ->toArray());
    }

    protected function registerLivewireComponentDirectory(string $directory, string $namespace, string $aliasPrefix = ''): void
    {
        $filesystem = app(Filesystem::class);

        if (!$filesystem->isDirectory($directory)) {
            return;
        }

        collect($filesystem->allFiles($directory))
            ->map(function (SplFileInfo $file) use ($namespace): string {
                return (string) Str::of($namespace)
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn (string $class): bool => is_subclass_of($class, Component::class) && (!(new ReflectionClass($class))->isAbstract()))
            ->each(function (string $class) use ($namespace, $aliasPrefix): void {
                $alias = Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->prepend($aliasPrefix)
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');

                Livewire::component($alias, $class);
            });
    }
}
