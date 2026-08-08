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
    // config/di-console.php is an optional config-plugin file that only matters to consumers who wire up
    // yiisoft/di (or another container providing Yiisoft\Definitions\Reference) themselves; that container
    // already requires yiisoft/definitions, so requiring it here too would only force an unrelated PHP 8.1+
    // floor onto every consumer, even those not using this optional config.
    ->ignoreErrorsOnPackages(['yiisoft/definitions'], [ErrorType::SHADOW_DEPENDENCY]);
