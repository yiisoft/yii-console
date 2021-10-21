<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use RuntimeException;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Yiisoft\Test\Support\Log\SimpleLogger;
use Yiisoft\Yii\Console\ErrorListener;

final class ErrorListenerTest extends TestCase
{
    public function testOnError()
    {
        $logger = new SimpleLogger();
        $errorListener = new ErrorListener($logger);

        $command = $this->application()->find('serve');

        $consoleErrorEvent = new ConsoleErrorEvent(
            new ArrayInput([]),
            new ConsoleOutput(),
            new RuntimeException('Can not execute command'),
            $command,
        );

        $errorListener->onError($consoleErrorEvent);

        $this->assertCount(1, $logger->getMessages());
        $this->assertStringContainsString('serve', $logger->getMessages()[0]['message']);
    }

    public function testOnErrorWithoutCommand()
    {
        $logger = new SimpleLogger();
        $errorListener = new ErrorListener($logger);

        $consoleErrorEvent = new ConsoleErrorEvent(
            new ArrayInput([]),
            new ConsoleOutput(),
            new RuntimeException('Can not execute command'),
        );

        $errorListener->onError($consoleErrorEvent);

        $this->assertCount(1, $logger->getMessages());
        $this->assertStringContainsString('unknown', $logger->getMessages()[0]['message']);
    }
}
