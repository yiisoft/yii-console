<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

use function sprintf;

final class ErrorListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function onError(ConsoleErrorEvent $event): void
    {
        $exception = $event->getError();
        $command = $event->getCommand();

        $commandName = ($command !== null && $command->getName() !== null) ? $command->getName() : 'unknown';

        $message = sprintf(
            '%s: %s in %s:%s while running console command "%s".',
            $exception::class,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $commandName,
        );

        $this->logger->error($message, ['exception' => $exception]);
    }
}
