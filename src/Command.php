<?php
namespace Yiisoft\Yii\Console;

interface Command
{
    public function isInteractive(): bool;
    public function description(): string;
    public function help(): string;

    // can do as Symfony with "configure"
    public function run(Input $input, Output $output): void;
}
