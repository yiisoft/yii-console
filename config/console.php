<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;

return [
    Application::class => static function (ContainerInterface $container) use ($params) {
        $application = new Application();

        $dispatcher = $container->get(SymfonyEventDispatcher::class);
        $application->setDispatcher($dispatcher);

        $loader = new ContainerCommandLoader(
            $container,
            $params['console']['commands']
        );
        $application->setCommandLoader($loader);

        $name = $params['console']['name'] ?? null;
        if ($name !== null) {
            $application->setName($name);
        }

        $version = $params['console']['version'] ?? null;
        if ($version !== null) {
            $application->setVersion($version);
        }
        $application->setAutoExit(false);
        return $application;
    },
];
