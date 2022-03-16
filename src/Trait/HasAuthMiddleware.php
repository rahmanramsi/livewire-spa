<?php

namespace Rahmanramsi\LivewireSpa\Trait;

use Illuminate\Support\Facades\Auth;

trait HasAuthMiddleware
{
  public function mountHasAuthMiddleware()
  {
    // check if the page has middleware auth
    $middleware = $this::getMiddleware();
    if (in_array('auth', $middleware) && !Auth::check()) {
      // user not logged in redirect to login page
      $this->skipRender();
      return $this->redirectRoute('login');
    }
  }
}
