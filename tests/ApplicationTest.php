<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Console\Event\ApplicationStartup;
use Yiisoft\Yii\Console\Event\ApplicationShutdown;

final class ApplicationTest extends TestCase
{
    public function testDispatcherEventApplicationStartup(): void
    {
        $event = new ApplicationStartup();

        $dispatcher = $this->application->getDispatcher();
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
    }

    public function testDispatcherEventApplicationShutdown(): void
    {
        $event = new ApplicationShutdown(ExitCode::OK);

        $dispatcher = $this->application->getDispatcher();
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
        $this->assertEquals(ExitCode::OK, $event->getExitCode());
    }

    public function testDoRenderThrowable(): void
    {
        $command = $this->application->find('stub');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $this->assertEquals(
            0,
            $commandCreate->execute(['command' => $command->getName()])
        );

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            'ConsoleException',
            $output
        );

        $this->assertStringContainsString(
            'Test solution',
            $output
        );
        
        $this->assertStringContainsString(
            'StubCommand->execute()',
            $output
        );
    }

    public function testDoRenderThrowableWithStyledOutput(): void
    {
        $command = $this->application->find('stub');

        $commandCreate = new CommandTester($command);

        $this->assertEquals(
            0,
            $commandCreate->execute(['command' => $command->getName(), '--styled' => true])
        );

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            'ConsoleException',
            $output
        );

        $this->assertStringContainsString(
            'Test solution',
            $output
        );
        
        $this->assertStringContainsString(
            'StubCommand->execute()',
            $output
        );
    }

    public function testRenamedCommand(): void
    {
        $command = $this->application->find('stub/rename');

        $commandCreate = new CommandTester($command);

        $this->assertEquals(
            ExitCode::OK,
            $commandCreate->execute(['command' => $command->getName()])
        );
    }
}
