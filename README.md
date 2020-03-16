# Auto test your laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imanghafoori/laravel-self-test.svg?style=flat-square)](https://packagist.org/packages/imanghafoori1/laravel-self-test)
[![Build Status](https://img.shields.io/travis/imanghafoori1/laravel-self-test/master.svg?style=flat-square)](https://travis-ci.org/imanghafoori1/laravel-self-test)
[![Quality Score](https://img.shields.io/scrutinizer/g/imanghafoori1/laravel-self-test.svg?style=flat-square)](https://scrutinizer-ci.com/g/imanghafoori1/laravel-self-test)
[![Total Downloads](https://img.shields.io/packagist/dt/imanghafoori/laravel-self-test.svg?style=flat-square)](https://packagist.org/packages/imanghafoori1/laravel-self-test)

This package provides a way to find errors without writing any tests. For example when you setup an event handler which does not exist at all, notifies you.

## Installation

You can install the package via composer:

```bash
composer require imanghafoori/laravel-self-test
```

## Usage

You can run:
``` php
php artisan event:check
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email imanghafoori1@gmail.com instead of using the issue tracker.

## Credits

- [Iman](https://github.com/imanghafoori1)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
