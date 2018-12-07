<?php

return [
    'app' => [
        '__class' => \yii\console\Application::class,
        'controllerNamespace' => \yii\console\controllers::class,
        'controllerMap' => [
            'asset' => [
                '__class' => \yii\console\controllers\AssetController::class,
            ],
            'cache' => [
                '__class' => \yii\console\controllers\CacheController::class,
            ],
            'fixture' => [
                '__class' => \yii\console\controllers\FixtureController::class,
            ],
            'help' => [
                '__class' => \yii\console\controllers\HelpController::class,
            ],
            'message' => [
                '__class' => \yii\console\controllers\MessageController::class,
            ],
            'migrate' => [
                '__class' => \yii\console\controllers\MigrateController::class,
            ],
            'serve' => [
                '__class' => \yii\console\controllers\ServeController::class,
            ],
        ],
    ],

    'request' => [
        '__class' => \yii\console\Request::class,
    ],
    'response' => [
        '__class' => \yii\console\Response::class,
    ],
    'errorHandler' => [
        '__class' => \yii\console\ErrorHandler::class,
    ],
];
