<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Symfony\Component\Console\Tester\CommandCompletionTester;
use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\ExitCode;

final class ServeCommandTest extends TestCase
{
    public function testServeCommandExecuteWithoutArguments(): void
    {
        $command = $this->application()->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $this->assertEquals(
            2,
            $commandCreate->execute([])
        );

        $output = $commandCreate->getDisplay(true);

        $this->assertSame(Serve::EXIT_CODE_NO_DOCUMENT_ROOT, $commandCreate->getStatusCode());

        $this->assertStringContainsString(
            '[ERROR] Document root',
            $output
        );
    }

    public function testServeCommandExecuteWithDocRoot(): void
    {
        $command = $this->application()->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $output = $commandCreate->getDisplay(true);

        $this->assertSame(ExitCode::OK, $commandCreate->getStatusCode());

        $this->assertStringContainsString(
            'Server started on http://127.0.0.1:8080/',
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
        $command = $this->application()->find('serve');

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

            $this->assertSame(Serve::EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_PROCESS, $commandCreate->getStatusCode());

            $this->assertStringContainsString(
                '[ERROR] http://127.0.0.1:8080 is taken by another process.',
                $output
            );

            socket_close($socket);
        }
    }

    public function testErrorWhenRouterDoesNotExist(): void
    {
        $command = $this->application()->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--router' => 'index.php',
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $output = $commandCreate->getDisplay(true);

        $this->assertSame(Serve::EXIT_CODE_NO_ROUTING_FILE, $commandCreate->getStatusCode());

        $this->assertStringContainsString(
            '[ERROR] Routing file "index.php" does not exist.',
            $output
        );
    }

    public function testSuccess(): void
    {
        $command = $this->application()->find('serve');

        $commandCreate = new CommandTester($command);

        $commandCreate->setInputs(['yes']);

        $commandCreate->execute([
            '--router' => 'tests/public/index.php',
            '--docroot' => 'tests',
            '--env' => 'test',
        ]);

        $this->assertSame(ExitCode::OK, $commandCreate->getStatusCode());

        $output = $commandCreate->getDisplay(true);

        $this->assertStringContainsString(
            'Server started on http://127.0.0.1:8080/',
            $output
        );

        $this->assertStringContainsString(
            'Routing file is "tests/public/index.php"',
            $output
        );
    }

    /**
     * @dataProvider completionSuggestionsProvider
     */
    public function testAutocompletion(array $input, array $suggestions): void
    {
        $command = $this->application()->find('serve');

        $commandTester = new CommandCompletionTester($command);

        $this->assertSame($suggestions, $commandTester->complete($input));
    }

    public function completionSuggestionsProvider(): array
    {
        return [
            'address' => [
                [''],
                ['localhost', '127.0.0.1', '0.0.0.0'],
            ],
            'address --' => [
                ['localhost', ''],
                ['--port', '--docroot', '--router', '--env']
            ],
        ];
    }
}
