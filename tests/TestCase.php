<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use PHPUnit\Framework\TestCase as AbstractTestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use ReflectionObject;
use ReflectionException;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputOption;
use Yiisoft\Di\Container;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;
use Yiisoft\Yii\Console\Tests\Stub\StubCommand;

class TestCase extends AbstractTestCase
{
    protected Application $application;
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

    /**
     * Invokes a inaccessible method.
     *
     * @param $object
     * @param $method
     * @param array $args
     * @param bool $revoke whether to make method inaccessible after execution
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    protected function invokeMethod(object $object, string $method, array $args = [], bool $revoke = true)
    {
        $reflection = new ReflectionObject($object);

        $method = $reflection->getMethod($method);

        $method->setAccessible(true);

        $result = $method->invokeArgs($object, $args);

        if ($revoke) {
            $method->setAccessible(false);
        }

        return $result;
    }

    protected function configContainer(): void
    {
        $this->container = new Container($this->config());
        $this->application = $this->container->get(Application::class);
    }

    private function config(): array
    {
        $params = $this->params();

        return [
            ListenerProviderInterface::class => Provider::class,

            EventDispatcherInterface::class => Dispatcher::class,

            CommandLoaderInterface::class => [
                'class' => CommandLoader::class,
                'constructor' => [
                    'commandMap' => $params['yiisoft/yii-console']['commands'],
                ],
            ],

            Application::class => [
                'class' => Application::class,
                'callMethods' => [
                    'setDispatcher' => [Reference::to(SymfonyEventDispatcher::class)],
                    'setCommandLoader' => [Reference::to(CommandLoaderInterface::class)],
                    'addOptions' => [
                        new InputOption(
                            'config',
                            'c',
                            InputOption::VALUE_REQUIRED,
                            'Set alternative configuration name'
                        )
                    ],
                    'setName' => [$params['yiisoft/yii-console']['name']],
                    'setVersion' => [$params['yiisoft/yii-console']['version']],
                    'setAutoExit' => [$params['yiisoft/yii-console']['autoExit']],
                ],
            ],
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
                    'stub' => StubCommand::class,
                    'stub/rename' => StubCommand::class,
                ],
                'version' => '3.0',
                'rebuildConfig' => static fn () => getenv('APP_ENV') === 'dev',
            ],
        ];
    }
}
