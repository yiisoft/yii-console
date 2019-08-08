<?php
use Yiisoft\Yii\Console\Application;

return [
    Application::class => function (\Psr\Container\ContainerInterface $container) use ($params) {
        $app = new \Yiisoft\Yii\Console\Application();
        $commands = $container->get('commands');

        $loader = new \Symfony\Component\Console\CommandLoader\ContainerCommandLoader(
            $container,
            $params['commands']
        );
        $app->setCommandLoader($loader);
        return $app;
    },
    'commands' => new \Yiisoft\Factory\Definitions\ValueDefinition([
        'serve' => \Yiisoft\Yii\Console\Command\Serve::class,
    ]),

    // TODO: there should be no need for it
    \Yiisoft\Yii\Console\Command\Serve::class => \Yiisoft\Yii\Console\Command\Serve::class
];
