<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Symfony\Component\Console\Tester\CommandTester;

final class ListCommandTest extends TestCase
{
    public function testBase(): void
    {
        $command = new CommandTester(
            $this->application()->find('list')
        );

        $this->assertSame(0, $command->execute([]));

        $output = $command->getDisplay(true);

        $this->assertStringContainsString('Available commands:', $output);
        $this->assertStringContainsString('Runs PHP built-in web server', $output);
        $this->assertStringContainsString('stub/rename', $output);
    }

    public function testNamespace(): void
    {
        $command = new CommandTester(
            $this->application()->find('list')
        );

        $this->assertSame(0, $command->execute(['namespace' => 'stub']));

        $output = $command->getDisplay(true);

        $this->assertStringContainsString('Available commands for the "stub" namespace:', $output);
        $this->assertStringContainsString('stub/rename', $output);
        $this->assertStringNotContainsString('Runs PHP built-in web server', $output);
    }
}
