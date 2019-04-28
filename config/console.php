<?php

return [
    'app' => [
        '__class' => \Yiisoft\Yii\Console\Application::class,
        'controllerNamespace' => \Yiisoft\Yii\Console\Controllers::class,
        'controllerMap' => [
            'asset' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\AssetController::class,
            ],
            'cache' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\CacheController::class,
            ],
            'fixture' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\FixtureController::class,
            ],
            'help' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\HelpController::class,
            ],
            'message' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\MessageController::class,
            ],
            'migrate' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\MigrateController::class,
            ],
            'serve' => [
                '__class' => \Yiisoft\Yii\Console\Controllers\ServeController::class,
            ],
        ],
    ],

    'request' => [
        '__class' => \Yiisoft\Yii\Console\Request::class,
    ],
    'response' => [
        '__class' => \Yiisoft\Yii\Console\Response::class,
    ],
    'errorHandler' => [
        '__class' => \Yiisoft\Yii\Console\ErrorHandler::class,
    ],
];
