<?php

namespace Yiisoft\Yii\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

class Application extends \Symfony\Component\Console\Application
{
    public const VERSION = '3.0.0-dev';

    public function __construct(string $name = 'Yii Console', string $version = self::VERSION)
    {
        parent::__construct($name, $version);
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
