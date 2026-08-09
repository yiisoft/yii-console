<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->disableComposerAutoloadPathScan()
    ->setFileExtensions(['php'])
    ->addPathToScan(__DIR__ . '/config', isDev: false)
    ->addPathToScan(__DIR__ . '/src', isDev: false)
    ->addPathToScan(__DIR__ . '/tests', isDev: true)
    // `yiisoft/definitions` is used only in `config/di-console.php`, which are loaded by
    // consumers using `yiisoft/di`, that already requires `yiisoft/definitions` itself.
    ->ignoreErrorsOnPackageAndPath('yiisoft/definitions', __DIR__ . '/config/di-console.php', [ErrorType::SHADOW_DEPENDENCY]);
