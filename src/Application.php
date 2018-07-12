<?php
namespace Yiisoft\Yii\Console;

class Application extends \Symfony\Component\Console\Application
{
    public const VERSION = '1.0.0';

    public function __construct(string $name = 'Yii Console', string $version = self::VERSION)
    {
        parent::__construct($name, $version);
    }
}
