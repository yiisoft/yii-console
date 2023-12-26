<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Stub;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

#[AsCommand('error', 'Error command tests')]
final class ErrorCommand extends Command
{
    public function __construct(private NonExistsClass $class)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return ExitCode::OK;
    }
}
