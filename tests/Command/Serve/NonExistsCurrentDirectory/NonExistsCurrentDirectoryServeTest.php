<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Command\Serve\NonExistsCurrentDirectory;

use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\Tests\TestCase;

final class NonExistsCurrentDirectoryServeTest extends TestCase
{
    public function testBase(): void
    {
        $command = $this->application()->find('serve');

        $commandCreate = new CommandTester($command);

        $directory = __DIR__ . DIRECTORY_SEPARATOR . 'test-dir';
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        chdir($directory);
        rmdir($directory);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to get current working directory.');
        $commandCreate->execute([]);
    }
}
