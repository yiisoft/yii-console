<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use PHPUnit\Framework\TestCase as AbstractTestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Di\Container;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;

class TestCase extends AbstractTestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configContainer();
    }

    protected function tearDown(): void
    {
        unset($this->container);
    }

    protected function configContainer(): void
    {
        $this->container = new Container($this->config());
    }

    private function config(): array
    {
        $params = $this->params();

        return [
            ListenerProviderInterface::class => Provider::class,

            EventDispatcherInterface::class => Dispatcher::class,

            ContainerCommandLoader::class => [
                '__class' => ContainerCommandLoader::class,
                '__construct()' => [
                    'commandMap' => $params['yiisoft/yii-console']['commands']
                ]
            ],

            Application::class => [
                '__class' => Application::class,
                'setDispatcher()' => [Reference::to(SymfonyEventDispatcher::class)],
                'setCommandLoader()' => [Reference::to(ContainerCommandLoader::class)],
                'addOptions()' => [new InputOption(
                    'config',
                    'c',
                    InputOption::VALUE_REQUIRED,
                    'Set alternative configuration name'
                )],
                'setName()' => [$params['yiisoft/yii-console']['name']],
                'setVersion()' => [$params['yiisoft/yii-console']['version']],
                'setAutoExit()' => [$params['yiisoft/yii-console']['autoExit']]
            ]
        ];
    }

    private function params(): array
    {
        return [
            'yiisoft/yii-console' => [
                'id' => 'yii-console',
                'name' => 'Yii Console',
                'autoExit' => false,
                'commands' => [
                    'serve' => Serve::class,
                ],
                'version' => '3.0',
                'rebuildConfig' => static fn () => getenv('APP_ENV') === 'dev',
            ],
        ];
    }
}
