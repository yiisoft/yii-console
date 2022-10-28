<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Event;

final class ApplicationShutdown
{
    public function __construct(private int $exitCode)
    {
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }
}
