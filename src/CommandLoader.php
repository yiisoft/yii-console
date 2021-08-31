<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

final class CommandLoader implements CommandLoaderInterface
{
    private ContainerInterface $container;

    /**
     * @var array<string, string>
     */
    private array $commandMap;

    /**
     * @param array<string, string> $commandMap An array with command names as keys and service ids as values
     */
    public function __construct(ContainerInterface $container, array $commandMap)
    {
        $this->container = $container;
        $this->commandMap = $commandMap;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        /** @var Command $commandClass */
        $commandClass = $this->commandMap[$name];
        $description = $commandClass::getDefaultDescription();

        if ($description === null) {
            return $this->getCommandInstance($name);
        }

        return new LazyCommand(
            $name,
            [],
            $description,
            false,
            function () use ($name) {
                return $this->getCommandInstance($name);
            }
        );
    }

    private function getCommandInstance(string $name): Command
    {
        /** @var Command $command */
        $command = $this->container->get($this->commandMap[$name]);
        if ($command->getName() !== $name) {
            $command->setName($name);
        }

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name)
    {
        return isset($this->commandMap[$name]) && $this->container->has($this->commandMap[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getNames()
    {
        return array_keys($this->commandMap);
    }
}
