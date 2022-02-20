<?php

return [

  'path' => 'app',

  'layout' => 'layouts.app',

  'single_page' => \Rahmanramsi\LivewireSpa\Http\Livewire\SinglePage::class,

  'pages' => [
    'default' => null,
    'error' => \Rahmanramsi\LivewireSpa\Http\Livewire\Pages\ErrorPage::class,
    'namespace' => 'App\\LivewireSpa',
    'path' => app_path('LivewireSpa'),
    'register' => [],
  ],

  'middleware' => ['web'],

  'livewire' => [
    'namespace' => 'App\\LivewireSpa',
    'path' => app_path('LivewireSpa'),
  ],
];
