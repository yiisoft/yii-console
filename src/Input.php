<?php
namespace Yiisoft\Yii\Console;

interface Input
{
    public function commandName(): string;

    public function option(string $name);
    public function options(): array;

    /**
     * Arguments are ordered input separated by spaces
     */
    public function arguments(): array;

    public function prompt($message, $default = null);

    public function confirm($message, $default = false): bool;
}
