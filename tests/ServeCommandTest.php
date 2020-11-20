<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\Application;

final class ServeCommandTest extends TestCase
{
    public function testServeCommandExecuteWithoutArguments(): void
    {
        $application = $this->container->get(Application::class);

        $command = $application->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $this->assertEquals(
            2,
            $commandCreate->execute([])
        );

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            '[ERROR] Document root',
            $output
        );
    }

    public function testServeCommandExecuteWithDocRoot(): void
    {
        $application = $this->container->get(Application::class);

        $command = $application->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            'Server started on http://localhost:8080/',
            $output
        );

        $this->assertStringContainsString(
            'Document root is',
            $output
        );

        $this->assertStringContainsString(
            'Quit the server with CTRL-C or COMMAND-C.',
            $output
        );
    }
}
