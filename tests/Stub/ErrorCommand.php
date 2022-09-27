<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Stub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

final class ErrorCommand extends Command
{
    protected static $defaultName = 'error';
    protected static $defaultDescription = 'Error command tests';

    public function __construct(private NonExistsClass $class)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return ExitCode::OK;
    }
}
