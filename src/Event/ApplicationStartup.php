<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Event;

final class ApplicationStartup
{
    public function __construct(
        public array $arguments = [],
        public array $options = [],
    ) {
    }
}
