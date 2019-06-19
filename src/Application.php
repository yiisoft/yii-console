<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Yii\Console;

use Yiisoft\Yii\Console\Commands\Help;
use Yiisoft\Yii\Console\Exceptions\UnknownCommandException;
use yii\exceptions\InvalidRouteException;

/**
 * Application represents a console application.
 *
 * Application extends from [[\yii\base\Application]] by providing functionalities that are
 * specific to console requests. In particular, it deals with console requests
 * through a command-based approach:
 *
 * - A console application consists of one or several possible user commands;
 * - Each user command is implemented as a class extending [[\Yiisoft\Yii\Console\Controller]];
 * - User specifies which command to run on the command line;
 * - The command processes the user request with the specified parameters.
 *
 * The command classes should be under the namespace specified by [[controllerNamespace]].
 * Their naming should follow the same naming convention as controllers. For example, the `help` command
 * is implemented using the `HelpController` class.
 *
 * To run the console application, enter the following on the command line:
 *
 * ```
 * yii <route> [--param1=value1 --param2 ...]
 * ```
 *
 * where `<route>` refers to a controller route in the form of `ModuleID/ControllerID/ActionID`
 * (e.g. `sitemap/create`), and `param1`, `param2` refers to a set of named parameters that
 * will be used to initialize the controller action (e.g. `--since=0` specifies a `since` parameter
 * whose value is 0 and a corresponding `$since` parameter is passed to the action method).
 *
 * A `help` command is provided by default, which lists available commands and shows their usage.
 * To use this command, simply type:
 *
 * ```
 * yii help
 * ```
 */
class Application
{
    private $commands = [
        'help' => Help::class,
    ];

    public function addCommand(Command $command): void
    {
        $this->commands[] = $command;
    }

    public function run(Input $input, Output $output): int
    {
        $commandName = $input->commandName() ?? 'help';
        if (!isset($this->commands[$commandName])) {
            throw new UnknownCommandException($commandName, $this);
        }

        // TODO: execute via DI to support constructor injection
        return (new $commandName($input, $output))->run();
    }
}
