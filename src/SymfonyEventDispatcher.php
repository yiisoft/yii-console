<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SymfonyEventDispatcher implements EventDispatcherInterface
{
    public function __construct(private PsrEventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * @psalm-template T as object
     *
     * @psalm-param T $event
     *
     * @psalm-return T
     */
    public function dispatch(object $event, ?string $eventName = null): object
    {
        /** @psalm-var T */
        return $this->dispatcher->dispatch($event);
    }
}
