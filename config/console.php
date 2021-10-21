<?php

declare(strict_types=1);

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputOption;
use Yiisoft\Definitions\Reference;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\CommandLoader;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;

/** @var array $params */

return [
    CommandLoaderInterface::class => [
        'class' => CommandLoader::class,
        '__construct()' => [
            'commandMap' => $params['yiisoft/yii-console']['commands'],
        ],
    ],

    Application::class => [
        '__construct()' => [
            $params['yiisoft/yii-console']['name'],
            $params['yiisoft/yii-console']['version'],
        ],
        'setCommandLoader()' => [Reference::to(CommandLoaderInterface::class)],
        'setDispatcher()' => [Reference::to(SymfonyEventDispatcher::class)],
        'setAutoExit()' => [$params['yiisoft/yii-console']['autoExit']],
        'addOptions()' => [
            new InputOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'Set alternative configuration name'
            ),
        ],
    ],
];
