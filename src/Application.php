<?php

namespace Yiisoft\Yii\Console;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;
use Yiisoft\Yii\Console\Event\ApplicationShutdown;
use Yiisoft\Yii\Console\Event\ApplicationStartup;

class Application extends \Symfony\Component\Console\Application
{
    public const VERSION = '3.0.0-dev';

    private EventDispatcherInterface $dispatcher;

    public function __construct(string $name = 'Yii Console', string $version = self::VERSION)
    {
        parent::__construct($name, $version);
    }

    public function setDispatcher(\Symfony\Contracts\EventDispatcher\EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        parent::setDispatcher($dispatcher);
    }

    public function start(): void
    {
        $this->dispatcher->dispatch(new ApplicationStartup());
    }

    public function shutdown(): void
    {
        $this->dispatcher->dispatch(new ApplicationShutdown());
    }

    public function doRenderException(\Exception $e, OutputInterface $output)
    {
        parent::doRenderException($e, $output);
        // Friendly Exception support
        if ($e instanceof FriendlyExceptionInterface) {
            if ($output instanceof StyleInterface) {
                $output->title($e->getName());
                $output->note($e->getSolution());
                $output->newLine();
            } else {
                $output->writeln([
                    '<fg=red>' . $e->getName() . '</>',
                    '<fg=yellow>' . $e->getSolution() . '</>',
                    '',
                ]);
            }
        }
    }
}
