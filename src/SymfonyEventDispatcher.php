<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SymfonyEventDispatcher implements EventDispatcherInterface
{
    private PsrEventDispatcherInterface $dispatcher;

    public function __construct(PsrEventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(object $event, string $eventName = null): object
    {
        return $this->dispatcher->dispatch($event);
    }
}
