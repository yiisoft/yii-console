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
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fyii-console%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/yii-console/master)
[![static analysis](https://github.com/yiisoft/yii-console/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/yii-console/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/yii-console/coverage.svg)](https://shepherd.dev/github/yiisoft/yii-console)

The binary of the package is available as `vendor/bin/yii <command>`.

To start working with the package you must do one of these:

- `composer require yiisoft/di`
- Manually create a `console.container` option in `params` (see [Composer config plugin](https://github.com/yiisoft/composer-config-plugin) to get more info)
- Use your own binary file instead of `vendor/bin/yii`

Since the package is based on [Symfony Console component](https://symfony.com/doc/current/components/console.html),
refer to its documentation for details on how to use the binary and create your own commands.

### Using alternative set of configurations 

Use option `--config` (`-c`) for set name of alterntaive configuration:

```
vendor\bin\yii -ctest
vendor\bin\yii --config=test
``` 

For more info about alternative configuration sets their and usage see its  
[documentation](https://github.com/yiisoft/composer-config-plugin/blob/master/docs/en/alternatives.md).

### Console parameters

##### rebuildConfig
 
Type: `bool|\Closure`

Default Value: 
```php
static fn() => getenv('APP_ENV') === 'dev'
```

Force rebuild configuration before each run.

Don't do it in production, assembling takes it's time.

#### Override parameters

You can override this params via `config/params.php` in your application. For example:

```php
return [
    'yiisoft/yii-console' => [
        'rebuildConfig' => true,
    ],
];
```

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Framework Console is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
