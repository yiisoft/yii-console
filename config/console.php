<?php

return [
    'app' => [
        '__class' => yii\console\Application::class,
        'controllerMap' => [
            'asset'     => yii\console\controllers\AssetController::class,
            'cache'     => yii\console\controllers\CacheController::class,
            'fixture'   => yii\console\controllers\FixtureController::class,
            'help'      => yii\console\controllers\HelpController::class,
            'message'   => yii\console\controllers\MessageController::class,
            'migrate'   => yii\console\controllers\MigrateController::class,
            'serve'     => yii\console\controllers\ServeController::class,
        ],
    ],

    'request' => [
        '__class' => yii\console\Request::class,
    ],
    'response' => [
        '__class' => yii\console\Response::class,
    ],
    'errorHandler' => [
        '__class' => yii\console\ErrorHandler::class,
    ],
];
