<?php

namespace Yiisoft\Yii\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ConsoleBufferedOutput extends ConsoleOutput
{
    private string $buffer = '';

    /**
     * Returns buffer content optionally clearing it.
     *
     * @param bool $clearBuffer
     * @return string
     */
    public function fetch(bool $clearBuffer = false): string
    {
        $content = $this->buffer;
        if ($clearBuffer) {
            $this->buffer = '';
        }

        return $content;
    }

    protected function doWrite(string $message, bool $newline)
    {
        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= PHP_EOL;
        }

        parent::doWrite($message, $newline);
    }
}
