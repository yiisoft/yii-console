<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

use function array_shift;
use function explode;

final class CommandLoader implements CommandLoaderInterface
{
    /**
     * @psalm-var array<string, array{
     *     name: non-empty-string,
     *     aliases: non-empty-string[],
     *     class: class-string<Command>,
     *     hidden: bool,
     * }>
     */
    private array $commandMap;

    /**
     * @var string[]
     */
    private array $commandNames;

    /**
     * @param array $commandMap An array with command names as keys and service ids as values.
     *
     * @psalm-param array<string, class-string<Command>> $commandMap
     */
    public function __construct(private ContainerInterface $container, array $commandMap)
    {
        $this->setCommandMap($commandMap);
    }

    public function get(string $name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        $commandName = $this->commandMap[$name]['name'];
        $commandAliases = $this->commandMap[$name]['aliases'];
        $commandClass = $this->commandMap[$name]['class'];
        $commandHidden = $this->commandMap[$name]['hidden'];

        $description = $commandClass::getDefaultDescription();

        if ($description === null) {
            return $this->getCommandInstance($name);
        }

        return new LazyCommand(
            $commandName,
            $commandAliases,
            $description,
            $commandHidden,
            fn () => $this->getCommandInstance($name),
        );
    }

    public function has(string $name): bool
    {
        return isset($this->commandMap[$name]);
    }

    public function getNames(): array
    {
        return $this->commandNames;
    }

    private function getCommandInstance(string $name): Command
    {
        $commandName = $this->commandMap[$name]['name'];
        $commandClass = $this->commandMap[$name]['class'];
        $commandAliases = $this->commandMap[$name]['aliases'];

        /** @var Command $command */
        $command = $this->container->get($commandClass);

        if ($command->getName() !== $commandName) {
            $command->setName($commandName);
        }

        if ($command->getAliases() !== $commandAliases) {
            $command->setAliases($commandAliases);
        }

        return $command;
    }

    /**
     * @psalm-param array<string, class-string<Command>> $commandMap
     */
    private function setCommandMap(array $commandMap): void
    {
        $this->commandMap = [];
        $this->commandNames = [];

        foreach ($commandMap as $name => $class) {
            $aliases = explode('|', $name);

            $hidden = false;
            if ($aliases[0] === '') {
                $hidden = true;
                array_shift($aliases);
            }

            /** @var string[] $aliases Fix for psalm. See {@link https://github.com/vimeo/psalm/issues/9261}. */

            $this->validateAliases($aliases);

            $primaryName = array_shift($aliases);

            $item = [
                'name' => $primaryName,
                'aliases' => $aliases,
                'class' => $class,
                'hidden' => $hidden,
            ];

            $this->commandMap[$primaryName] = $item;
            $this->commandNames[] = $primaryName;

            foreach ($aliases as $alias) {
                $this->commandMap[$alias] = $item;
            }
        }
    }

    /**
     * @psalm-param string[] $aliases
     *
     * @psalm-assert non-empty-string[] $aliases
     */
    private function validateAliases(array $aliases): void
    {
        foreach ($aliases as $alias) {
            if ($alias === '') {
                throw new RuntimeException('Do not allow empty command name or alias.');
            }
        }
    }
}
