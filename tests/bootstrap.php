<?php
use hiqdev\composer\config\Builder;
use yii\di\Container;
use yii\helpers\Yii;

// ensure we get report on all possible php errors
error_reporting(E_ALL);

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

(function () {
    $composerAutoload = __DIR__ . '/../vendor/autoload.php';
    if (!is_file($composerAutoload)) {
        die('You need to set up the project dependencies using Composer');
    }

    require_once $composerAutoload;

    $container = new Container(require Builder::path('tests'));

    Yii::setContainer($container);
})();
