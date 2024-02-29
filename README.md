![Pest Laravel Expectations](https://banners.beyondco.de/Laravel%20Telegraph.png?theme=light&packageManager=composer+require&packageName=defstudio%2Ftelegraph&pattern=architect&style=style_1&description=Telegram+bots+made+easy&md=1&showWatermark=1&fontSize=100px&images=phone-outgoing)

<a href="https://packagist.org/packages/defstudio/telegraph" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/v/defstudio/telegraph.svg?style=flat&cacheSeconds=3600" alt="Latest Version on Packagist"></a>
<a href="https://github.com/defstudio/telegraph/actions?query=workflow%3Arun-tests+branch%3Amain" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/telegraph/run-tests.yml?branch=main&label=tests&cacheSeconds=3600&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAABiVBMVEUAAAD/iPv9yP3Xm+j/mP//wfVj67Je6bP/h/pVx6p6175d57WQycf+iPn/iPrsnezArd3+t/qpvNJd6LP/jPpu6rv/lPr/kPpc57T/rvtc57Np6rj3oPl37cL/tfn/wv9d6brX//L/g/rYn+n/gvrWm+di6LX+jPrskfGWzMpt6bln4bdd57Jk6LWSycj+vPquwNVo6rde6bP7nvvYnup91b/+vfv/lvtc57OqvNTFs9//t/td57L9t/r/iPpd6LPapej/ovp26bxy67v9lfld6LJr4Ljwsvb/xv3/jv39zv1t6buG5cTDreH5ivlc5rJy676V4cxb57D/y/h50MOy4OCUxcVa77X/iPpe6LP/jP+pu9L8t///tvuQycfArNxp6LzArd151r7/i/9n4bb/j/9e6rT/ifr7ifrskvLYnuhi87tg8blg7bf/vv//lP+wxNtj9b3/qv//oP/+ivz/l/r8ifryn/fvlPTfpPDeofDKtujHtOWX1NF/4seC3cR82sFu7cBo5LiMwPMrAAAAWHRSTlMA/Wv8FAIC/dME/Wj+3tEG/Pv798G1oHRjS0k1LBsWDgsJ/v36+fTy8ezn4+Lh29XNzMzLysLAwLSwr66opJqakY+Ni4J7end0bGlpY11XU048KicmIR8fizl+vwAAAVdJREFUKM9tz2VXAlEQgOFBBURpkE67u7u7E1YFYQl1SbvrlztDiLvss+fc/fCemXMvAEhhKqU759P1rLoxUDUyEh9fPH0z7ALiVrEY+SSNtxNS2upouYv7hOL191aKVsZHUTgbnQPQgDkq4ctHdoQmTWmW4WFzlVUDVpNKXf2fWpWbZIwUq/hcmjWGYnSa1pZZjEoomrEdVAisD7CX6GEb40rqTODxCj21OjDOvjRV8l2jhudBDchg/FUbDIZCITzwQyH6a9+2AMDbm9GfltFnxgAdtQWUgQJl4VQq37uPcSnsfYZzav6Ew18fQ4fUYPM7Qn4uSiIdyx5saJ6T+/3+5KSltshicwI2UpfAKE/aoARTvnn7KMYMdlAUyWRSyHN2JeU42HlCi4TszTHcmuj3iMVdP5JzoyAWNzi6T3ZGrMFCliK3BAqRSC/B2+6IxvYYNcO+2Npfv+yFi10LfBUAAAAASUVORK5CYII=" alt="Tests"></a>
<a href="https://github.com/defstudio/telegraph/actions?query=workflow%3Alint+branch%3Amain" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/telegraph/php-cs-fixer.yml?branch=main&label=code%20style&cacheSeconds=3600" alt="Code Style"></a>
<a href="https://github.com/defstudio/telegraph/actions?query=workflow%3Aphpstan+branch%3Amain" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/telegraph/phpstan.yml?branch=main&label=phpstan&cacheSeconds=3600" alt="Static Analysis"></a>
<a href="https://packagist.org/packages/defstudio/telegraph" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/dt/defstudio/telegraph.svg?style=flat&cacheSeconds=3600" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/defstudio/telegraph" target="_blank"><img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/l/defstudio/telegraph?style=flat&cacheSeconds=3600" alt="License"></a>
<a href="https://twitter.com/FabioIvona?ref_src=twsrc%5Etfw"><img alt="Twitter Follow" src="https://img.shields.io/twitter/follow/FabioIvona?label=Follow&style=social"></a>

---


**Telegraph** is a Laravel package for fluently interacting with Telegram Bots made by [def:studio](https://twitter.com/FabioIvona)

```php
Telegraph::message('hello world')
    ->keyboard(Keyboard::make()->buttons([
            Button::make('Delete')->action('delete')->param('id', '42'),
            Button::make('open')->url('https://test.it'),
    ]))->send();
```

## Installation

You can install the package via composer:

```bash
composer require defstudio/telegraph
```

Publish and launch required migrations:

```bash
php artisan vendor:publish --tag="telegraph-migrations"
```

```bash
php artisan migrate
```

Optionally, you can publish the config and translation file with:
```bash
php artisan vendor:publish --tag="telegraph-config"
```
```bash
php artisan vendor:publish --tag="telegraph-translations"
```

## Usage & Documentation

After a new bot is created and added to a chat/group/channel (as described [in our documentation](https://defstudio.github.io/telegraph/quickstart/new-bot)),
the `Telegraph` facade can be used to easily send messages and interact with it:

```php
Telegraph::message('this is great')->send();
```

An extensive documentation is available at

https://defstudio.github.io/telegraph

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. [Follow Us](https://twitter.com/FabioIvona) on Twitter for more updates about this package.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fabio Ivona](https://github.com/defstudio)
- [Andrea Marco Sartori](https://github.com/cerbero90) for his cool ideas
- [Alberto Pieripolli](https://github.com/trippo) Pest badge
- [Joris Drenth](https://github.com/jorisdrenth) Docs fix and upgrade
- [All Contributors](../../contributors)

## Translators

- [Tievo](https://github.com/Tievodj) Spanish
- [Andrey Helldar](https://github.com/andrey-helldar) Russian
- [Joris Drenth](https://github.com/jorisdrenth) Dutch
- [Moayed Alhagy](https://github.com/moayedalhagy) Arabic


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
