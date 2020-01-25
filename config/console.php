<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Yiisoft\Yii\Console\Application;

return [
    Application::class => function (ContainerInterface $container) use ($params) {
        $app = new Application();
        $loader = new ContainerCommandLoader(
            $container,
            $params['console']['commands']
        );
        $app->setCommandLoader($loader);

        $name = $params['console']['name'] ?? null;
        if ($name !== null) {
            $app->setName($name);
        }

        $version = $params['console']['version'] ?? null;
        if ($version !== null) {
            $app->setVersion($version);
        }

        return $app;
    },
];
