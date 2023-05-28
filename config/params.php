<?php

declare(strict_types=1);

use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Command\Game;
use Yiisoft\Yii\Console\Command\Serve;

return [
    'yiisoft/yii-console' => [
        'name' => Application::NAME,
        'version' => Application::VERSION,
        'autoExit' => false,
        'commands' => [
            'serve' => Serve::class,
            '|yii' => Game::class,
        ],
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
];
