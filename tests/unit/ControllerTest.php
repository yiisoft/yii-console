<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\console\tests\unit;

use yii\base\Module;
use yii\console\Request;
use yii\console\Response;
use yii\tests\TestCase;

/**
 * @group console
 */
class ControllerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->app->controllerMap = [
            'fake' => FakeController::class,
            'help' => FakeHelpController::class,
        ];
    }

    public function testBindActionParams()
    {
        $controller = new FakeController('fake', $this->app);

        $params = ['from params'];
        [$fromParam, $other] = $controller->run('aksi1', $params);
        $this->assertEquals('from params', $fromParam);
        $this->assertEquals('default', $other);

        $params = ['from params', 'notdefault'];
        [$fromParam, $other] = $controller->run('aksi1', $params);
        $this->assertEquals('from params', $fromParam);
        $this->assertEquals('notdefault', $other);

        $params = ['d426,mdmunir', 'single'];
        $result = $controller->runAction('aksi2', $params);
        $this->assertEquals([['d426', 'mdmunir'], 'single'], $result);

        $params = ['', 'single'];
        $result = $controller->runAction('aksi2', $params);
        $this->assertEquals([[], 'single'], $result);

        $params = ['_aliases' => ['t' => 'test']];
        $result = $controller->runAction('aksi4', $params);
        $this->assertEquals('test', $result);

        $params = ['_aliases' => ['a' => 'testAlias']];
        $result = $controller->runAction('aksi5', $params);
        $this->assertEquals('testAlias', $result);

        $params = ['_aliases' => ['ta' => 'from params,notdefault']];
        [$fromParam, $other] = $controller->runAction('aksi6', $params);
        $this->assertEquals('from params', $fromParam);
        $this->assertEquals('notdefault', $other);

        $params = ['test-array' => 'from params,notdefault'];
        [$fromParam, $other] = $controller->runAction('aksi6', $params);
        $this->assertEquals('from params', $fromParam);
        $this->assertEquals('notdefault', $other);

        $params = ['avaliable'];
        $message = $this->app->t('yii', 'Missing required arguments: {params}', ['params' => implode(', ', ['missing'])]);
        $this->expectException(\yii\console\exceptions\Exception::class);
        $this->expectExceptionMessage($message);
        $result = $controller->runAction('aksi3', $params);
    }

    public function assertResponseStatus($status, $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($status, $response->exitStatus);
    }

    public function runRequest($route, $args = 0)
    {
        $request = new Request($this->app);
        $request->setParams(func_get_args());
        return $this->app->handleRequest($request);
    }

    public function testResponse()
    {
        $status = 123;

        $response = $this->runRequest('fake/status');
        $this->assertResponseStatus(0, $response);

        $response = $this->runRequest('fake/status', (string) $status);
        $this->assertResponseStatus($status, $response);

        $response = $this->runRequest('fake/response');
        $this->assertResponseStatus(0, $response);

        $response = $this->runRequest('fake/response', (string) $status);
        $this->assertResponseStatus($status, $response);
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/12028
     */
    public function testHelpOptionNotSet()
    {
        $controller = new FakeController('posts', $this->app);
        $controller->runAction('index');

        $this->assertTrue(FakeController::getWasActionIndexCalled());
        $this->assertNull(FakeHelpController::getActionIndexLastCallParams());
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/12028
     */
    public function testHelpOption()
    {
        $controller = new FakeController('posts', $this->app);
        $controller->help = true;
        $controller->runAction('index');

        $this->assertFalse(FakeController::getWasActionIndexCalled());
        $this->assertEquals(FakeHelpController::getActionIndexLastCallParams(), ['posts/index']);
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/13071
     */
    public function testHelpOptionWithModule()
    {
        $controller = new FakeController('posts', new Module('news', $this->app));
        $controller->help = true;
        $controller->runAction('index');

        $this->assertFalse(FakeController::getWasActionIndexCalled());
        $this->assertEquals(FakeHelpController::getActionIndexLastCallParams(), ['news/posts/index']);
    }


    /**
     * Tests if action help does not include (class) type hinted arguments.
     * @see #10372
     */
    public function testHelpSkipsTypeHintedArguments()
    {
        $controller = new FakeController('fake', $this->app);
        $help = $controller->getActionArgsHelp($controller->createAction('with-complex-type-hint'));

        $this->assertArrayNotHasKey('typedArgument', $help);
        $this->assertArrayHasKey('simpleArgument', $help);
    }
}
