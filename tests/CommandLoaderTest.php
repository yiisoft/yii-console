<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use RuntimeException;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;

final class CommandLoaderTest extends TestCase
{
    private CommandLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader = $this->container()->get(CommandLoaderInterface::class);
    }

    public function testGetNames(): void
    {
        $this->assertSame(['serve', 'stub', 'stub/rename'], $this->loader->getNames());
    }

    public function testGetAlias(): void
    {
        $this->assertSame([], $this->loader->get('serve')->getAliases());
        $this->assertSame(['st'], $this->loader->get('st')->getAliases());
        $this->assertSame(['st'], $this->loader->get('stub')->getAliases());
        $this->assertSame([], $this->loader->get('stub/rename')->getAliases());
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
}
