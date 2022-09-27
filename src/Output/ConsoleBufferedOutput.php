<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput;

final class ConsoleBufferedOutput extends ConsoleOutput
{
    private string $buffer = '';

    /**
     * Returns buffer content optionally clearing it.
     */
    public function fetch(bool $clearBuffer = false): string
    {
        $content = $this->buffer;

        if ($clearBuffer) {
            $this->buffer = '';
        }

        return $content;
    }

    protected function doWrite(string $message, bool $newline): void
    {
        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= PHP_EOL;
        }

        parent::doWrite($message, $newline);
    }
}
