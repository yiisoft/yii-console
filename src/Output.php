<?php
namespace Yiisoft\Yii\Console;

interface Output
{
    public const QUIET = -1;
    public const NORMAL = 0;
    public const VERBOSE = 1;
    public const VERY_VERBOSE = 2;
    public const DEBUG = 3;

    public function write(string $message, int $verbosity = 0): void;
    public function writeLn(string $message, int $verbosity = 0): void;
}
