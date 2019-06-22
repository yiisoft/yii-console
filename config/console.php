<?php
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use yii\di\Reference;
use Yiisoft\Yii\Console\Application;

return [
    'app' => [
        '__class' => Application::class,
        'setCommandLoader()' => Reference::to(CommandLoaderInterface::class),
    ],
    CommandLoaderInterface::class => function (ContainerInterface $container) {
        return new ContainerCommandLoader($container, [
            'serve' => 'serve',
        ]);
    },

    // commands
    'serve' => \Yiisoft\Yii\Console\Command\Serve::class,
];
