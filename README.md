# Create SPA using Laravel Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rahmanramsi/livewire-spa.svg?style=flat-square)](https://packagist.org/packages/rahmanramsi/livewire-spa)
[![Total Downloads](https://img.shields.io/packagist/dt/rahmanramsi/livewire-spa.svg?style=flat-square)](https://packagist.org/packages/rahmanramsi/livewire-spa)

A wrapper for making SPA using Laravel Livewire.

# Requirements

1. PHP 8.x or higher
2. Livewire 2.x or higher

# Installation

```bash
composer require rahmanramsi/livewire-spa
```

## Configuring The Layout Component

By default, LivewireSpa will render Page Component into the `{{ $slot }}` of a blade layout component located at: `resources/views/layouts/app.blade.php`

You must create `resources/views/layouts/app.blade.php` file first and add your custom layout there.

## Include The Assets

Add `{{ slot }}` inside the body, and add the following Blade directives in the `head` tag, and before the end `body` tag in your template.

```php
<html>
<head>
    ...
    @livewireStyles
    @livewireSpaStyles
</head>
<body>

    {{ $slot }}

    @livewireScripts
    @livewireSpaScripts
</body>
</html>
```

## Publishing The Config File

```bash
php artisan vendor:publish --tag="livewire-spa-config"
```

This is the contents of the published config file:

```php
return [

  'path' => 'spa',

  'layout' => 'layouts.app',

  'single_page' => \Rahmanramsi\LivewireSpa\Http\Livewire\SinglePage::class,

  'pages' => [
    'default' => null,
    'error' => \Rahmanramsi\LivewireSpa\Http\Livewire\Pages\ErrorPage::class,
    'namespace' => 'App\\Http\\LivewireSpa',
    'path' => app_path('Http/LivewireSpa'),
    'register' => [],
  ],

  'middleware' => ['web'],

  'livewire' => [
    'namespace' => 'App\\Http\\LivewireSpa',
    'path' => app_path('Http/LivewireSpa'),
  ],
];
```

# Usage

## Creating Page

You can create a page by using the following command:

```bash
php artisan make:livewire-spa PageName
```

Two new files were created in your project:

`app\LivewireSpa\PageName.php`

`resources\views\livewire-spa\page-name.blade.php`

If you wish to create Page within sub-folders, you can use the following different syntaxes:

```bash
php artisan make:livewire-spa SubFolder/PageName
```

Now, the two created files will be in sub-folders:

`app\LivewireSpa\SubFolder\PageName.php`

`resources\views\livewire-spa\sub-folder\page-name.blade.php`

## Navigate Between Page

Just use default `a[href]` tag, it detect it. If you don't want LivewireSpa to prevent the default behavior, just add `native` attribute to `a` tag.

# Roadmap

- [ ] Write the test
- [ ] Demo Page
- [ ] Documentation Page
- [ ] Starterpack

# Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

# Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

# Credits

- [Rahman Ramsi](https://github.com/rahmanramsi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
