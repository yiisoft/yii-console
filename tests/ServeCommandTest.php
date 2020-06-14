<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Composer\Config\Builder;
use Yiisoft\Di\Container;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\ExitCode;

final class ServeCommandTest extends \PHPUnit\Framework\TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container(
            require Builder::path('console'),
            require Builder::path('providers-console')
        );
    }

    protected function tearDowm(): void
    {
        parent::tearDown();

        unset($this->container);
    }

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
            '--docroot' => 'tests/data',
            '--env' => 'test'
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
