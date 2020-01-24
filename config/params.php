<?php

return [
    'app.id' => 'yii-console',
    'app.name' => 'Yii Console',
    'commands' => [
        'serve' => \Yiisoft\Yii\Console\Command\Serve::class,
    ],
    'container' => static function():Yiisoft\Di\Container {
        if (!class_exists('Yiisoft\Di\Container')) {
            $message = 'You must either install yiisoft/di or create "container" configuration parameter'
                . ' containing a callable which will return configured implementation of PSR-11 DI container.';
            throw new \hiqdev\composer\config\exceptions\BadConfigurationException($message);
        }

        return new Yiisoft\Di\Container(require \hiqdev\composer\config\Builder::path('console'));
    }
];
