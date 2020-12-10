<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Stub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Console\Application;

class StubCommand extends Command
{
    private Application $application;
    protected static $defaultName = 'stub';

    public function configure(): void
    {
        $this
            ->setDescription('Stub command tests');
    }

    public function __construct(Application $application)
    {
        $this->application = $application;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->application->start();

        $exception = new ConsoleException();
        $style = new SymfonyStyle($input, $output);

        $this->application->doRenderException($exception, $output);

        $this->application->doRenderException($exception, $style);

        $this->application->shutdown(ExitCode::OK);

        return ExitCode::OK;
    }
}
