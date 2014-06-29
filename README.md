Rollbar integration for Laravel
===============================

[![Build Status](https://travis-ci.org/atijust/laravel-rollbar.svg?branch=master)](https://travis-ci.org/atijust/laravel-rollbar) [![Coverage Status](https://img.shields.io/coveralls/atijust/laravel-rollbar.svg)](https://coveralls.io/r/atijust/laravel-rollbar?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/53b0083b404aa650dd000012/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53b0083b404aa650dd000012)

Installation
------------

Add `atijust/laravel-rollbar` to your `composer.json`:

```json
{
    "require": {
        "atijust/laravel-rollbar": "dev-master"
    }
}
```

Add the service provider in `app/config/app.php`:

```php
'Atijust\LaravelRollbar\LaravelRollbarServiceProvider',
```

Publish the configuration file:

```bash
php artisan config:publish atijust/laravel-rollbar
```

Add your rollbar access token in `app/config/packages/atijust/laravel-rollbar/config.php`:

```php
'access_token' => 'your rollbar access token',
```

Usage
-----

```php
// to report exceptions
Log::error($exception);

// to send log-like messages 
Log::info('message', ['foo' => 'bar']);
```
