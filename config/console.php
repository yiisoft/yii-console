<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Yiisoft\Yii\Console\Application;

return [
    Application::class => function (ContainerInterface $container) use ($params) {
        $app = new Application();
        $loader = new ContainerCommandLoader(
            $container,
            $params['commands']
        );
        $app->setCommandLoader($loader);
        return $app;
    },
];
