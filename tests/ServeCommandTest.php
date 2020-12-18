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

    public function testErrorWhenAddressIsTaken(): void
    {
        $application = $this->container->get(Application::class);

        $command = $application->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        if (PHP_OS_FAMILY === 'Windows') {
            $commandCreate->execute([
                'address' => '127.0.0.1:445',
                '--docroot' => 'tests',
                '--env' => 'test',
            ]);

            $output = $commandCreate->getDisplay(true);

            $this->assertStringContainsString(
                '[ERROR] http://127.0.0.1:445 is taken by another process.',
                $output
            );
        } else {
            $socket = socket_create_listen(8080);

            $commandCreate->execute([
                'address' => '127.0.0.1:8080',
                '--docroot' => 'tests',
                '--env' => 'test',
            ]);

            $output = $commandCreate->getDisplay(true);

            $this->assertStringContainsString(
                '[ERROR] http://127.0.0.1:8080 is taken by another process.',
                $output
            );

            socket_close($socket);
        }
    }

    public function testErrorWhenRouterDoesNotExist(): void
    {
        $application = $this->container->get(Application::class);

        $command = $application->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--router' => 'index.php',
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            '[ERROR] Routing file "index.php" does not exist.',
            $output
        );
    }

    public function testSuccess(): void
    {
        $application = $this->container->get(Application::class);

        $command = $application->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--router' => 'tests/public/index.php',
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            'Server started on http://localhost:8080/',
            $output
        );

        $this->assertStringContainsString(
            'Routing file is "tests/public/index.php"',
            $output
        );
    }
}
