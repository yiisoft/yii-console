<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

use function sprintf;

final class ErrorListener
{
    public function __construct(private ?LoggerInterface $logger = null)
    {
    }

    public function onError(ConsoleErrorEvent $event): void
    {
        if ($this->logger === null) {
            return;
        }

        $exception = $event->getError();
        $command = $event->getCommand();

        $message = sprintf(
            '%s: %s in %s:%s while running console command "%s".',
            $exception::class,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $command?->getName() ?? 'unknown',
        );

        $this->logger->error($message, ['exception' => $exception]);
    }
}
