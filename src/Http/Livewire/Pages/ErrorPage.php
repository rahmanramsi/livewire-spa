<?php

namespace Rahmanramsi\LivewireSpa\Http\Livewire\Pages;

use Rahmanramsi\LivewireSpa\Pages\Page;

class ErrorPage extends Page
{

  protected static string $route = 'error';

  public function render()
  {
    return view('livewire-spa::pages.error');
  }
}
