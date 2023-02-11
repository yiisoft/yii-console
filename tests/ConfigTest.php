<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;

final class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testContainer(): void
    {
        $container = $this->createContainer();

        $commandLoader = $container->get(CommandLoaderInterface::class);
        $serve = $container->get(Serve::class);
        $application = $container->get(Application::class);

        $this->assertInstanceOf(CommandLoader::class, $commandLoader);
        $this->assertInstanceOf(Serve::class, $serve);
        $this->assertInstanceOf(Application::class, $application);
    }

    private function createContainer(): Container
    {
        $config = $this->getConfig();
        return new Container(
            ContainerConfig::create()->withDefinitions(
                $config->get('di-console')
                +
                [
                    EventDispatcherInterface::class => new SimpleEventDispatcher(),
                ]
            )
        );
    }

    private function getConfig(): Config
    {
        return new Config(
            new ConfigPaths(dirname(__DIR__), 'config'),
            mergePlanFile: '../tests/environment/.merge-plan.php'
        );
    }
}
