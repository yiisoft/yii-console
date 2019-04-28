<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Yii\Console\Tests\Controllers;

use Yiisoft\Yii\Console\Controllers\CacheController;

/**
 * CacheController that discards output.
 */
class SilencedCacheController extends CacheController
{
    /**
     * {@inheritdoc}
     */
    public function stdout($string)
    {
        // do nothing
    }
}
