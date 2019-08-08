<?php
use Yiisoft\Yii\Console\Application;

return [
    Application::class => function (\Psr\Container\ContainerInterface $container) use ($params) {
        $app = new \Yiisoft\Yii\Console\Application();
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
];
