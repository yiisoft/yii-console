<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    ContainerInterface::class => static function (ContainerInterface $container) {
        return $container;
    },
];
