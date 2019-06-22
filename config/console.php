<?php

return [
    'app' => function (\Psr\Container\ContainerInterface $container) {
        $app = new \Yiisoft\Yii\Console\Application();
        $loader = new \Symfony\Component\Console\CommandLoader\ContainerCommandLoader($container, [
            'serve' => 'serve',
        ]);
        $app->setCommandLoader($loader);
        return $app;
    },

    // commands
    'serve' => \Yiisoft\Yii\Console\Command\Serve::class,
];
