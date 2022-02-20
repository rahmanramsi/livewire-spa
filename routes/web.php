<?php

use Illuminate\Support\Facades\Route;
use Rahmanramsi\LivewireSpa\Facades\LivewireSpa;
use Rahmanramsi\LivewireSpa\Http\Livewire\SinglePage;
use Rahmanramsi\LivewireSpa\Http\Controllers\AssetController;

Route::middleware(config('livewire-spa.middleware', 'web'))->prefix(config('livewire-spa.path'))->name('livewire-spa.')->group(function () {
  Route::get('/', config('livewire-spa.single_page', SinglePage::class))->name('app');
  Route::get('/assets/{file}', AssetController::class)->where('file', '.*')->name('assets');

  foreach (LivewireSpa::getPages() as $page) {
    Route::get($page::getRoute(), config('livewire-spa.single_page', SinglePage::class))->name($page::getSlug());
  };
});
