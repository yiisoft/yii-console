<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Event;

final class ApplicationShutdown
{
    private int $exitCode;

    public function __construct(int $exitCode)
    {
        $this->exitCode = $exitCode;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }
}
