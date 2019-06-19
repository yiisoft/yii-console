<?php
namespace Yiisoft\Yii\Console;

class ArgvInput implements Input
{
    public function commandName(): string
    {
        return $argv[0];
    }

    public function option(string $name)
    {
    }

    public function parameters(): array
    {
    }
}
