<?php

declare(strict_types=1);

use Yiisoft\Composer\Config\Builder;
use Yiisoft\Yii\Console\Provider\ApplicationProvider;
use Yiisoft\Yii\Event\EventDispatcherProvider;

return [
    'yiisoft/yii-console/application' => [
        '__class' => ApplicationProvider::class,
        '__construct()' => [
            $params['yiisoft/yii-console']['commands'],
            $params['yiisoft/yii-console']['name'],
            $params['yiisoft/yii-console']['version']
        ],
    ],
    'yiisoft/event-dispatcher/eventdispatcher' => [
        '__class' => EventDispatcherProvider::class,
        '__construct()' => [Builder::require('events-console')],
    ],
];
