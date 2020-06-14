<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii Framework Console</h1>
    <br>
</p>

This Yii Framework package adds console into the application.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii-console/v/stable.png)](https://packagist.org/packages/yiisoft/yii-console)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii-console/downloads.png)](https://packagist.org/packages/yiisoft/yii-console)
[![Build status](https://github.com/yiisoft/yii-console/workflows/build/badge.svg)](https://github.com/yiisoft/yii-console/actions)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/yii-console/badges/coverage.png)](https://scrutinizer-ci.com/g/yiisoft/yii-console/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/yiisoft/yii-console/badges/quality-score.png)](https://scrutinizer-ci.com/g/yiisoft/yii-console/)
[![static analysis](https://github.com/yiisoft/yii-console/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/yii-console/actions?query=workflow%3A%22static+analysis%22)

The binary of the package is available as `vendor/bin/yii <command>`.

To start working with the package you must do one of these:

- `composer require yiisoft/di`
- Manually create a `console.container` option in `params` (see [Composer config plugin](https://github.com/yiisoft/composer-config-plugin) to get more info)
- Use your own binary file instead of `vendor/bin/yii`

Since the package is based on [Symfony Console component](https://symfony.com/doc/current/components/console.html),
refer to its documentation for details on how to use the binary and create your own commands.

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```php
./vendor/bin/phpunit
```

### Static analysis

The code is statically analyzed with [Phan](https://github.com/phan/phan/wiki). To run static analysis:

```php
./vendor/bin/phan
```
