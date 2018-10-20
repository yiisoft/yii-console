<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\console\tests\unit;

use yii\console\Application;
use yii\console\exceptions\UnknownCommandException;
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
            'cache'     => \yii\console\controllers\CacheController::class,
            'migrate'   => \yii\console\controllers\MigrateController::class,
            'message'   => \yii\console\controllers\MessageController::class,
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
