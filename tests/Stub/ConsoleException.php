<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests\Stub;

use Exception;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class ConsoleException extends Exception implements FriendlyExceptionInterface
{
    public function getName(): string
    {
        return 'ConsoleException';
    }

    public function getSolution(): string
    {
        return 'Test solution';
    }
}
