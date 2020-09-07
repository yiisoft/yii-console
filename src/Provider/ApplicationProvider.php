<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Provider;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;
use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\SymfonyEventDispatcher;

final class ApplicationProvider extends ServiceProvider
{
    private array $commands;
    private string $name;
    private string $version;

    public function __construct(array $commands = [], string $name = '', string $version = '')
    {
        $this->commands = $commands;
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * @suppress PhanAccessMethodProtected
     */
    public function register(Container $container): void
    {
        $container->set(Application::class, function (ContainerInterface $container) {
            $application = new Application();

            $dispatcher = $container->get(SymfonyEventDispatcher::class);
            $application->setDispatcher($dispatcher);

            $loader = new ContainerCommandLoader(
                $container,
                $this->commands
            );

            $application->setCommandLoader($loader);

            $application->getDefinition()->addOptions([
                new InputOption('config', 'c', InputOption::VALUE_REQUIRED, 'Set alternative configuration name'),
                new InputOption('rebuild-config', 'r', InputOption::VALUE_NONE, 'Force rebuild configuration'),
            ]);

            if ($this->name !== '') {
                $application->setName($this->name);
            }

            if ($this->version !== '') {
                $application->setVersion($this->version);
            }

            $application->setAutoExit(false);

            return $application;
        });
    }
}
