# ExportCsv

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

A Laravel package for hassle free generate CSV files from Database, Export all Data from just one command.

## Installation

Via Composer

``` bash
$ composer require dipenparmar12/exportcsv
```

## Usage

Syntax

> `php artisan csv:export tables=<table-1>,<table-2>`

Example

> `php artisan csv:export tables=users,posts,comments`

Export all tables

> `php artisan csv:export -all-table`

shorthand

> `php artisan csv:export -a`

Export all tables with `force` option.
> `php artisan csv:export -af`

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

-   [Dipen Parmar](https://dipen.xyz)
-   [All Contributors](../../contributors)

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/dipenparmar12/exportcsv.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dipenparmar12/exportcsv.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/dipenparmar12/exportcsv/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/dipenparmar12/exportcsv
[link-downloads]: https://packagist.org/packages/dipenparmar12/exportcsv
[link-travis]: https://travis-ci.org/dipenparmar12/exportcsv
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/dipenparmar12
[link-contributors]: ../../contributors
