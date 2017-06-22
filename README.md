<h1>Laravel Toolkit</h1>

<a href="https://packagist.org/packages/otinsoft/laravel-toolkit"><img src="https://poser.pugx.org/otinsoft/laravel-toolkit/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://travis-ci.org/otinsoft/laravel-toolkit"><img src="https://travis-ci.org/otinsoft/laravel-toolkit.svg?branch=master" alt="Build Status"></a>

## Installation

You can install the package via composer:

```bash
composer require otinsoft/laravel-toolkit
```

Install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Otinsoft\Toolkit\ToolkitServiceProvider::class,
],
```

_(Optional)_ Publish the migrations with:

```bash
php artisan vendor:publish --provider="Otinsoft\Toolkit\ToolkitServiceProvider" --tag="migrations"
```

_(Optional)_ Publish the config file with:

```bash
php artisan vendor:publish --provider="Otinsoft\Toolkit\ToolkitServiceProvider" --tag="config"
```
