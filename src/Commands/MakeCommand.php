<?php

namespace Rahmanramsi\LivewireSpa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCommand extends Command
{
  use Concerns\CanManipulateFiles;

  protected $description = 'Creates a LivewireSpa class and view.';

  protected $signature = 'make:livewire-spa {name?} {--F|force}';

  public function handle(): int
  {
    $name = $this->argument('name');
    $page = (string) Str::of($name ?? $this->askRequired('Name (e.g. `Post`)', 'name'))
      ->trim('/')
      ->trim('\\')
      ->trim(' ')
      ->replace('/', '\\');
    $pageClass = (string) Str::of($page)->afterLast('\\');
    $pageNamespace = Str::of($page)->contains('\\') ?
      (string) Str::of($page)->beforeLast('\\') :
      '';

    $route = Str::of($name)->trim('\\')->trim(' ')->lower();
    $view = Str::of($page)
      ->prepend('livewire-spa\\')
      ->explode('\\')
      ->map(fn ($segment) => Str::kebab($segment))
      ->implode('.');

    $path = app_path(
      (string) Str::of($page)
        ->prepend('LivewireSpa\\')
        ->replace('\\', '/')
        ->append('.php'),
    );
    $viewPath = resource_path(
      (string) Str::of($view)
        ->replace('.', '/')
        ->prepend('views/')
        ->append('.blade.php'),
    );

    if (!$this->option('force') && $this->checkForCollision([
      $path,
      $viewPath,
    ])) {
      return static::INVALID;
    }


    $this->copyStubToApp('Page', $path, [
      'class' => $pageClass,
      'namespace' => 'App\\LivewireSpa' . ($pageNamespace !== '' ? "\\{$pageNamespace}" : ''),
      'view' => $view,
      'route' => $route,
    ]);

    $this->copyStubToApp('PageView', $viewPath);

    $this->info("Successfully created {$page}!");

    return static::SUCCESS;
  }
}
