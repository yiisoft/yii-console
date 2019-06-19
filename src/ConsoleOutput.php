<?php
namespace Yiisoft\Yii\Console;

class ConsoleOutput implements Output
{
    public function write(string $message): void
    {
        fwrite(STDOUT, $message);
    }

    public function writeLn(string $message): void
    {
        $this->write($message . "\n");
    }
}
