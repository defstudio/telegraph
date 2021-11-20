# A laravel facade to interact with Telegram Bots

[![Latest Version on Packagist](https://img.shields.io/packagist/v/defstudio/laravel-telegraph.svg?style=flat-square)](https://packagist.org/packages/defstudio/laravel-telegraph)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/def-studio/laravel-telegraph/run-tests?label=tests)](https://github.com/def-studio/laravel-telegraph/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/def-studio/laravel-telegraph/Check%20&%20fix%20styling?label=code%20style)](https://github.com/def-studio/laravel-telegraph/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/defstudio/laravel-telegraph.svg?style=flat-square)](https://packagist.org/packages/defstudio/laravel-telegraph)

---

**Telegraph** is a Laravel package that enables Telegram Bots interaction

```php
Telegraph::message('this is great')->send();
```

## Installation

You can install the package via composer:

```bash
composer require defstudio/laravel-telegraph
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="telegraph-config"
```

## Usage

```php
Telegraph::message('this is great')->send();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fabio Ivona](https://github.com/def:studio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
