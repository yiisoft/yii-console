<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use ReflectionClass;
use RuntimeException;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArgvInput;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;
use Yiisoft\Yii\Console\Output\ConsoleBufferedOutput;
use Yiisoft\Yii\Console\Tests\Stub\ErrorCommand;

use Yiisoft\Yii\Console\Tests\Stub\StubCommand;

use function class_exists;

final class CommandLoaderTest extends TestCase
{
    private CommandLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader = $this
            ->container()
            ->get(CommandLoaderInterface::class);
    }

    public function testGetNames(): void
    {
        $this->assertSame(['serve', 'stub', 'stub/rename'], $this->loader->getNames());
    }

    public function testGetAlias(): void
    {
        $this->assertSame([], $this->loader
            ->get('serve')
            ->getAliases());
        $this->assertSame(['st'], $this->loader
            ->get('st')
            ->getAliases());
        $this->assertSame(['st'], $this->loader
            ->get('stub')
            ->getAliases());
        $this->assertSame([], $this->loader
            ->get('stub/rename')
            ->getAliases());
    }

    public function testGetThrowExceptionIfCommandDoesNotExist(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->expectExceptionMessage('Command "not-found" does not exist.');

        $this->loader->get('not-found');
    }

    public function testConstructThrowExceptionIfCommandNameIsNotValid(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not allow empty command name or alias.');

        new CommandLoader(new SimpleContainer([Serve::class => new Serve()]), ['|' => Serve::class]);
    }

    public function testConstructThrowExceptionIfCommandAliasIsNotValid(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not allow empty command name or alias.');

        new CommandLoader(new SimpleContainer([Serve::class => new Serve()]), ['serve|' => Serve::class]);
    }

    public function testEmptyCommandAndAlias(): void
    {
        $container = new SimpleContainer();
        $commandMap = ['' => StubCommand::class];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not allow empty command name or alias.');

        new CommandLoader($container, $commandMap);
    }

    public function testConstructThrowExceptionIfItIsNotPossibleToCreateCommandObject(): void
    {
        $loader = new CommandLoader(
            new SimpleContainer([], static function () {
                $reflection = new ReflectionClass(ErrorCommand::class);
                $definition = $reflection
                    ->getConstructor()
                    ->getParameters()[0]
                    ->getType()
                    ->getName();

                if (class_exists($definition)) {
                    return $reflection->newInstanceArgs(new $definition());
                }

                throw new RuntimeException("Definition class \"$definition\" does not exist.");
            }),
            ['error' => ErrorCommand::class],
        );

        $this->assertTrue($loader->has('error'));

        $command = $loader->get('error');

        $this->assertInstanceOf(LazyCommand::class, $command);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Definition class "Yiisoft\Yii\Console\Tests\Stub\NonExistsClass" does not exist.',
        );

        $command->run(new ArgvInput(), new ConsoleBufferedOutput());
    }
}
