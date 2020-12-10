<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Yiisoft\Yii\Console\Output\ConsoleBufferedOutput;

final class ConsoleBufferedOutputTest extends TestCase
{
    public function testFetch(): void
    {
        $consoleBuffered = new ConsoleBufferedOutput();

        $this->assertEmpty($consoleBuffered->fetch());
    }

    public function testFetchClearBuffer(): void
    {
        $consoleBuffered = new ConsoleBufferedOutput();

        $this->assertEmpty($consoleBuffered->fetch(true));
    }

    public function testDoWrite(): void
    {
        $consoleBuffered = new ConsoleBufferedOutput();

        $this->invokeMethod($consoleBuffered, 'doWrite', ['testMe', false]);

        $result = $consoleBuffered->fetch();

        $this->assertEquals('testMe', $result);
    }

    public function testDoWriteTrue(): void
    {
        $consoleBuffered = new ConsoleBufferedOutput();

        $this->invokeMethod($consoleBuffered, 'doWrite', ['testMe', true]);

        $result = $consoleBuffered->fetch();

        $this->assertEquals('testMe' . PHP_EOL, $result);
    }
}
