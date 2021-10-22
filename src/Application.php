<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;
use Yiisoft\Yii\Console\Event\ApplicationShutdown;
use Yiisoft\Yii\Console\Event\ApplicationStartup;

use function array_slice;
use function explode;
use function implode;

class Application extends \Symfony\Component\Console\Application
{
    public const NAME = 'Yii Console';
    public const VERSION = '1.0.0-dev';

    private ?EventDispatcherInterface $dispatcher = null;

    public function __construct(string $name = self::NAME, string $version = self::VERSION)
    {
        parent::__construct($name, $version);
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
        parent::setDispatcher($dispatcher);
    }

    public function start(): void
    {
        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch(new ApplicationStartup());
        }
    }

    public function shutdown(int $exitCode): void
    {
        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch(new ApplicationShutdown($exitCode));
        }
    }

    public function renderThrowable(Throwable $e, OutputInterface $output): void
    {
        $output->writeln('', OutputInterface::VERBOSITY_QUIET);

        $this->doRenderThrowable($e, $output);
    }

    protected function doRenderThrowable(Throwable $e, OutputInterface $output): void
    {
        parent::doRenderThrowable($e, $output);
        // Friendly Exception support
        if ($e instanceof FriendlyExceptionInterface) {
            if ($output instanceof StyleInterface) {
                $output->title($e->getName());
                if (($solution = $e->getSolution()) !== null) {
                    $output->note($solution);
                }
                $output->newLine();
            } else {
                $output->writeln('<fg=red>' . $e->getName() . '</>');
                if (($solution = $e->getSolution()) !== null) {
                    $output->writeln('<fg=yellow>' . $solution . '</>');
                }
                $output->writeln('');
            }
        }

        $output->writeln($e->getTraceAsString());
    }

    public function addOptions(InputOption $options): void
    {
        $this->getDefinition()->addOption($options);
    }

    public function extractNamespace(string $name, int $limit = null)
    {
        $parts = explode('/', $name, -1);

        return implode('/', null === $limit ? $parts : array_slice($parts, 0, $limit));
    }
}
