<?php

namespace Yiisoft\Yii\Console\Input;

use Yiisoft\Yii\Console\Input;

class ExplicitInput implements Input
{
    private $commandName;
    private $arguments = [];
    private $options = [];

    public function __construct(string $commandName)
    {
        $this->commandName = $commandName;
    }

    public function withArguments(array $arguments): self
    {
        $new = clone $this;
        $new->arguments = $arguments;
        return $new;
    }

    public function withOptions(array $options): self
    {
        $new = clone $this;
        $new->options = $options;
        return $new;
    }

    public function withOption(string $name, $value): self
    {
        $new = clone $this;
        $new->options[$name] = $value;
        return $new;
    }

    public function commandName(): string
    {
        return $this->commandName;
    }

    public function option(string $name)
    {
        // TODO: Implement option() method.
    }

    public function parameters(): array
    {
        // TODO: Implement parameters() method.
    }

    public function arguments(): array
    {
        // TODO: Implement arguments() method.
    }

    public function prompt($message, $default = null)
    {
        // TODO: Implement prompt() method.
    }

    public function confirm($message, $default = false): bool
    {
        // TODO: Implement confirm() method.
    }
}
