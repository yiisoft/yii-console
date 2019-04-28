<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Yii\Console\Tests\Controllers;

/**
 * StdOutBufferControllerTrait is a trait, which can be applied to [[Yiisoft\Yii\Console\Controller]],
 * allowing to store all output into internal buffer instead of direct sending it to 'stdout'.
 */
trait StdOutBufferControllerTrait
{
    /**
     * @var string output buffer.
     */
    private $stdOutBuffer = '';

    public function stdout($string)
    {
        $this->stdOutBuffer .= $string;
    }

    public function flushStdOutBuffer()
    {
        $result = $this->stdOutBuffer;
        $this->stdOutBuffer = '';
        return $result;
    }
}
