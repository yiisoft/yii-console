<?php

use Yiisoft\Composer\Config\Builder;
use Psr\Container\ContainerInterface;
use Yiisoft\Yii\Console\Command\Serve;

return [
    'console' => [
        'id' => 'yii-console',
        'name' => 'Yii Console',
        'commands' => [
            'serve' => Serve::class,
        ],
        'container' => static function (): ContainerInterface {
            if (!class_exists(Yiisoft\Di\Container::class)) {
                $message = 'You must either install yiisoft/di or create "container" configuration parameter'
                    . ' containing a callable which will return configured implementation of PSR-11 DI container.';
                throw new \RuntimeException($message);
            }

            return new Yiisoft\Di\Container(require Builder::path('console'));
        }
    ],
];
