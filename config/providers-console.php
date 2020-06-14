<?php

declare(strict_types=1);

use Yiisoft\Yii\Console\Provider\ApplicationProvider;
use Yiisoft\Yii\Console\Provider\EventDispatcherProvider;

return [
    'yiisoft/event-dispatcher/eventdispatcher' => EventDispatcherProvider::class,
    'yiisoft/yii-console/application' => [
        '__class' => ApplicationProvider::class,
        '__construct()' => [
            $params['yiisoft/yii-console']['commands'],
            $params['yiisoft/yii-console']['name'],
            $params['yiisoft/yii-console']['version']
        ],
    ],
];
