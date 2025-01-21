<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii Console</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii-console/v)](https://packagist.org/packages/yiisoft/yii-console)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii-console/downloads)](https://packagist.org/packages/yiisoft/yii-console)
[![Build status](https://github.com/yiisoft/yii-console/actions/workflows/build.yml/badge.svg)](https://github.com/yiisoft/yii-console/actions/workflows/build.yml)
[![Code Coverage](https://codecov.io/gh/yiisoft/yii-console/branch/master/graph/badge.svg)](https://codecov.io/gh/yiisoft/yii-console)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fyii-console%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/yii-console/master)
[![static analysis](https://github.com/yiisoft/yii-console/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/yii-console/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/yii-console/coverage.svg)](https://shepherd.dev/github/yiisoft/yii-console)

Yii Console package provides a console that could be added to an application. This console is based on
[Symfony Console](https://github.com/symfony/console). The following extra features are added:

- lazy command loader;
- `SymfonyEventDispatcher` class that allows to use any [PSR-14](https://www.php-fig.org/psr/psr-14/) compatible event
  dispatcher with Symfony console;
- `ErrorListener` for logging console errors to any [PSR-3](https://www.php-fig.org/psr/psr-3/) compatible logger;
- console command `serve` that runs PHP built-in web server;
- raises events `ApplicationStartup` and `ApplicationShutdown` in console application;
- class `ExitCode` that contains constants for defining console command exit codes;
- `ConsoleBufferedOutput` that wraps `ConsoleOutput` and buffers console output.  

## Requirements

- PHP 8.0 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):

```shell
composer require yiisoft/yii-console
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
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Yiisoft\Yii\Console\ExitCode;


#[AsCommand(
    name: 'my:custom',
    description: 'Description of my custom command.'
)]
final class MyCustomCommand extends Command
{    
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

```shell
./yii serve
```

Your application will be accessible in your web browser at <http://localhost:8080> by default.
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

Alternatively, you can pass the settings through the console options.

> Tip: To run a web server with XDebug enabled, pass `--xdebug 1` to the command.

To see the available options, run `./yii serve --help`.

## Documentation

- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii Console is free software. It is released under the terms of the BSD License.
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
