<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Stub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\ExitCode;

class StubCommand extends Command
{
    private Application $application;
    protected static $defaultName = 'stub';

    public function configure(): void
    {
        $this
            ->setDescription('Stub command tests')
            ->addOption('styled', 's', InputOption::VALUE_OPTIONAL);
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

        $this->application->renderThrowable(
            $exception,
            $input->getOption('styled') ? new SymfonyStyle($input, $output) : $output
        );

        $this->application->shutdown(ExitCode::OK);

        return ExitCode::OK;
    }
}
