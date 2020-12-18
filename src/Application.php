<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;
use Throwable;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;
use Yiisoft\Yii\Console\Event\ApplicationShutdown;
use Yiisoft\Yii\Console\Event\ApplicationStartup;

class Application extends \Symfony\Component\Console\Application
{
    public const VERSION = '3.0.0-dev';

    /** @psalm-suppress PropertyNotSetInConstructor */
    private EventDispatcherInterface $dispatcher;

    public function __construct(string $name = 'Yii Console', string $version = self::VERSION)
    {
        parent::__construct($name, $version);
    }

    public function setDispatcher(SymfonyEventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
        parent::setDispatcher($dispatcher);
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function start(): void
    {
        $this->dispatcher->dispatch(new ApplicationStartup());
    }

    public function shutdown(int $exitCode): void
    {
        $this->dispatcher->dispatch(new ApplicationShutdown($exitCode));
    }

    protected function doRenderThrowable(Throwable $e, OutputInterface $output): void
    {
        parent::doRenderThrowable($e, $output);
        // Friendly Exception support
        if ($e instanceof FriendlyExceptionInterface) {
            if ($output instanceof StyleInterface) {
                $output->title($e->getName());
                if ($e->getSolution() !== null) {
                    $output->note((string)$e->getSolution());
                }
                $output->newLine();
            } else {
                $output->write('<fg=red>' . $e->getName() . '</>', true);
                if ($e->getSolution() !== null) {
                    $output->write('<fg=yellow>' . $e->getSolution() . '</>', true);
                }
                $output->write('', true);
            }
        }
    }

    public function addOptions(InputOption $options): void
    {
        $this->getDefinition()->addOption($options);
    }
}
