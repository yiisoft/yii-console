<?php

declare(strict_types=1);

use Yiisoft\Composer\Config\Builder;
use Psr\Container\ContainerInterface;
use Yiisoft\Yii\Console\Command\Serve;

return [
    'yiisoft/yii-console' => [
        'id' => 'yii-console',
        'name' => 'Yii Console',
        'commands' => [
            'serve' => Serve::class,
        ],
        'version' => '3.0'
    ],
];
