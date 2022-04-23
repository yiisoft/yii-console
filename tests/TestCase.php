<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionException;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;
use Yiisoft\Yii\Console\Tests\Stub\StubCommand;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createContainer();
    }

    protected function tearDown(): void
    {
        $this->container = null;
        $this->application = null;

        parent::tearDown();
    }

    protected function container(): ContainerInterface
    {
        if ($this->container === null) {
            $this->container = $this->createContainer();
        }

        return $this->container;
    }

    protected function application(): Application
    {
        return $this->container()->get(Application::class);
    }

    /**
     * Gets an inaccessible object property.
     *
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     */
    protected function getInaccessibleProperty(object $object, string $propertyName)
    {
        $reflection = new ReflectionObject($object);

        while (!$reflection->hasProperty($propertyName)) {
            $reflection = $reflection->getParentClass();
        }

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $result = $property->getValue($object);
        $property->setAccessible(false);

        return $result;
    }

    /**
     * Invokes an inaccessible method.
     *
     * @param object $object
     * @param string $method
     * @param array $args
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    protected function invokeMethod(object $object, string $method, array $args = [])
    {
        $reflection = new ReflectionObject($object);

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        $result = $method->invokeArgs($object, $args);
        $method->setAccessible(false);

        return $result;
    }

    private function createContainer(): ContainerInterface
    {
        $dispatcher = new SymfonyEventDispatcher(new SimpleEventDispatcher());

        $application = new Application();
        $application->setAutoExit(false);
        $application->setDispatcher($dispatcher);
        $application->addOptions(new InputOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Set alternative configuration name'
        ));

        $commandLoader = new CommandLoader(
            new SimpleContainer([
                Serve::class => new Serve(new Aliases(['@root' => __DIR__])),
                StubCommand::class => new StubCommand($application),
            ]),
            [
                'serve' => Serve::class,
                'stub|st' => StubCommand::class,
                'stub/rename' => StubCommand::class,
            ],
        );

        $application->setCommandLoader($commandLoader);

        return new SimpleContainer([
            Application::class => $application,
            EventDispatcherInterface::class => $dispatcher,
            CommandLoaderInterface::class => $commandLoader,
        ]);
    }
}
