<?php

namespace Yiisoft\Yii\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ConsoleBufferedOutput extends ConsoleOutput
{
    private $buffer = '';

    /**
     * Empties buffer and returns its content.
     *
     * @return string
     */
    public function fetch()
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite(string $message, bool $newline)
    {
        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= PHP_EOL;
        }

        parent::doWrite($message, $newline);
    }
}
