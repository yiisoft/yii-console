<?php

return [
    \Yiisoft\Yii\Console\Application::class => function (\Psr\Container\ContainerInterface $container) {
        $app = new \Yiisoft\Yii\Console\Application();
        $loader = new \Symfony\Component\Console\CommandLoader\ContainerCommandLoader(
            $container,
            $container->get('commands')
        );
        $app->setCommandLoader($loader);
        return $app;
    },
    'commands' => new \Yiisoft\Factory\Definitions\ValueDefinition([
        'serve' => \Yiisoft\Yii\Console\Command\Serve::class,
    ]),
];
