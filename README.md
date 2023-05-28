<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii Framework Console</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii-console/v/stable.png)](https://packagist.org/packages/yiisoft/yii-console)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii-console/downloads.png)](https://packagist.org/packages/yiisoft/yii-console)
[![Build status](https://github.com/yiisoft/yii-console/workflows/build/badge.svg)](https://github.com/yiisoft/yii-console/actions)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/yii-console/badges/coverage.png)](https://scrutinizer-ci.com/g/yiisoft/yii-console/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/yiisoft/yii-console/badges/quality-score.png)](https://scrutinizer-ci.com/g/yiisoft/yii-console/)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fyii-console%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/yii-console/master)
[![static analysis](https://github.com/yiisoft/yii-console/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/yii-console/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/yii-console/coverage.svg)](https://shepherd.dev/github/yiisoft/yii-console)

This Yii Framework package provides a console that could be added to an application.

## Requirements

- PHP 8.0 or higher.

## Installation

The package could be installed with composer:

```shell
composer require yiisoft/yii-console --prefer-dist
```

## General usage

In case you use one of Yii 3 standard application templates, console could be accessed as `./yii <command>`.

If not, then in the simplest use case in your console entry script do the following:

```php
#!/usr/bin/env php
<?php

declare(strict_types=1);

use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\CommandLoader;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->setCommandLoader(new CommandLoader(
    // Any container implementing `Psr\Container\ContainerInterface` for example:
    new Container(ContainerConfig::create()),
    // An array with command names as keys and service IDs as values:
    ['my/custom' => MyCustomCommand::class],
));

$app->run();
```

Since `\Yiisoft\Yii\Console\CommandLoader` uses lazy loading of commands, it's necessary
to specify the name and description in static properties when creating a command:

```php
use Symfony\Component\Console\Command\Command;
use Yiisoft\Yii\Console\ExitCode;

final class MyCustomCommand extends Command
{
    protected static $defaultName = 'my:custom';
    protected static $defaultDescription = 'Description of my custom command.';
    
    protected function configure(): void
    {
        // ...
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ...
        return ExitCode::OK;
    }
}
```

Run the console entry script with your command:

```shell
your-console-entry-script my/custom
```

> When naming commands use `:` as a separator. For example: `user:create`, `user:delete`, etc.


Since the package is based on [Symfony Console component](https://symfony.com/doc/current/components/console.html),
refer to its documentation for details on how to use the binary and create your own commands.

### Aliases and hidden commands

To configure commands, set the names and aliases in `\Yiisoft\Yii\Console\CommandLoader` configuration.
Names and aliases from the command class itself are always ignored.

The command can be marked as hidden by prefixing its name with `|`.


```php
'yiisoft/yii-console' => [
    'commands' => [
        'hello' => Hello::class, // name: 'hello', aliases: [], hidden: false
        'start|run|s|r' => Run::class, // name: 'start', aliases: ['run', 's', 'r'], hidden: false
        '|hack|h' => Hack::class, // name: 'hack', aliases: ['h'], hidden: true
    ],
],
```

### Runs PHP built-in web server

You can start local built-in web development server using the command:
```
./yii serve
```
Your application will be accessible in your web browser at http://localhost:8080 by default.
To configure default settings, set the options in `\Yiisoft\Yii\Console\CommandLoader` configuration.

```php
'yiisoft/yii-console' => [
    'serve' => [
        'appRootPath' => null,
        'options' => [
            'address' => '127.0.0.1',
            'port' => '8080',
            'docroot' => 'public',
            'router' => 'public/index.php',
        ],
    ],
],
```

Alternatively, you can pass the settings through the console options. To see the available options, run
`./yii serve --help`

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

## License

The Yii Framework Console is free software. It's released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
