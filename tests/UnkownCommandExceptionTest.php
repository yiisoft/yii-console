<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Yii\Console\Tests;

use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Exceptions\UnknownCommandException;
use yii\tests\TestCase;

/**
 * @group console
 */
class UnkownCommandExceptionTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication();
        $this->app->controllerMap = [
            'cache'     => \Yiisoft\Yii\Console\Controllers\CacheController::class,
            'migrate'   => \Yiisoft\Yii\Console\Controllers\MigrateController::class,
            'message'   => \Yiisoft\Yii\Console\Controllers\MessageController::class,
        ];
    }

    public function suggestedCommandsProvider()
    {
        return [
            ['migate', ['migrate']],
            ['mihate/u', ['migrate/up']],
            ['mirgte/u', ['migrate/up']],
            ['mirgte/up', ['migrate/up']],
            ['mirgte', ['migrate']],
            ['hlp', ['help']],
            ['ca', ['cache', 'cache/clear', 'cache/clear-all', 'cache/clear-schema', 'cache/index']],
            ['cach', ['cache', 'cache/clear', 'cache/clear-all', 'cache/clear-schema', 'cache/index']],
            ['cach/clear', ['cache/clear']],
            ['cach/clearall', ['cache/clear-all']],
            ['what?', []],
            // test UTF 8 chars
            ['ёлка', []],
            // this crashes levenshtein because string is longer than 255 chars
            [str_repeat('asdw1234', 31), []],
            [str_repeat('asdw1234', 32), []],
            [str_repeat('asdw1234', 33), []],
        ];
    }

    /**
     * @dataProvider suggestedCommandsProvider
     * @param string $command
     * @param array $expectedSuggestion
     */
    public function testSuggestCommand($command, $expectedSuggestion)
    {
        $exception = new UnknownCommandException($command, $this->app);
        $this->assertEquals($expectedSuggestion, $exception->getSuggestedAlternatives());
    }

    public function testNameAndConstructor()
    {
        $exception = new UnknownCommandException('test', $this->app);
        $this->assertEquals('Unknown command', $exception->getName());
        $this->assertEquals('test', $exception->command);
    }
}
