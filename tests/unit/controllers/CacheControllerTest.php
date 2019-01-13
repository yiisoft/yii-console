<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\console\tests\unit\controllers;

use yii\helpers\Yii;
use yii\cache\ArrayCache;
use yii\console\controllers\CacheController;
use yii\tests\TestCase;

/**
 * Unit test for [[\yii\console\controllers\CacheController]].
 * @see CacheController
 *
 * @group console
 * @group db
 * @group mysql
 */
class CacheControllerTest extends TestCase
{
    /**
     * @var SilencedCacheController
     */
    private $_cacheController;

    private $driverName = 'mysql';

    protected function setUp()
    {
        parent::setUp();

        $this->mockApplication();

        $this->_cacheController = Yii::createObject([
            '__class' => \yii\console\tests\unit\controllers\SilencedCacheController::class,
            'interactive' => false,
        ], [null, $this->app]); //id and module are null

        $databases = self::getParam('databases');
        $config = $databases[$this->driverName];
        $pdoDriver = 'pdo_' . $this->driverName;

        if (!extension_loaded('pdo') || !extension_loaded($pdoDriver)) {
            $this->markTestSkipped('pdo and ' . $pdoDriver . ' extensions are required.');
        }


        $this->container->setAll([
            'firstCache' => \yii\cache\ArrayCache::class,
            'secondCache' => function () {
                return new ArrayCache();
            },
            'session' => \yii\web\CacheSession::class, // should be ignored at `actionFlushAll()`
            'db' => [
                '__class' => $config['__class'] ?? \yii\db\Connection::class,
                'dsn' => $config['dsn'],
                'username' => $config['username'] ?? null,
                'password' => $config['password'] ?? null,
                'enableSchemaCache' => true,
                'schemaCache' => 'firstCache',
            ],
        ]);

        if (isset($config['fixture'])) {
            $this->app->db->open();
            $lines = explode(';', file_get_contents($config['fixture']));
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    $this->app->db->pdo->exec($line);
                }
            }
        }
    }

    public function testFlushOne()
    {
        $this->app->firstCache->set('firstKey', 'firstValue');
        $this->app->firstCache->set('secondKey', 'secondValue');
        $this->app->secondCache->set('thirdKey', 'thirdValue');

        $this->_cacheController->actionClear('firstCache');

        $this->assertNull($this->app->firstCache->get('firstKey'), 'first cache data should be flushed');
        $this->assertNull($this->app->firstCache->get('secondKey'), 'first cache data should be flushed');
        $this->assertEquals('thirdValue', $this->app->secondCache->get('thirdKey'), 'second cache data should not be flushed');
    }

    public function testClearSchema()
    {
        $schema = $this->app->db->schema;
        $this->app->db->createCommand()->createTable('test_schema_cache', ['id' => 'pk'])->execute();
        $noCacheSchemas = $schema->getTableSchemas('', true);
        $cacheSchema = $schema->getTableSchemas('', false);

        $this->assertEquals($noCacheSchemas, $cacheSchema, 'Schema should not be modified.');

        $this->app->db->createCommand()->dropTable('test_schema_cache')->execute();
        $noCacheSchemas = $schema->getTableSchemas('', true);
        $this->assertNotEquals($noCacheSchemas, $cacheSchema, 'Schemas should be different.');

        $this->_cacheController->actionClearSchema('db');
        $cacheSchema = $schema->getTableSchemas('', false);
        $this->assertEquals($noCacheSchemas, $cacheSchema, 'Schema cache should be flushed.');
    }

    public function testFlushBoth()
    {
        $this->app->firstCache->set('firstKey', 'firstValue');
        $this->app->firstCache->set('secondKey', 'secondValue');
        $this->app->secondCache->set('thirdKey', 'secondValue');

        $this->_cacheController->actionClear('firstCache', 'secondCache');

        $this->assertNull($this->app->firstCache->get('firstKey'), 'first cache data should be flushed');
        $this->assertNull($this->app->firstCache->get('secondKey'), 'first cache data should be flushed');
        $this->assertNull($this->app->secondCache->get('thirdKey'), 'second cache data should be flushed');
    }

    public function testNotFoundClear()
    {
        $this->app->firstCache->set('firstKey', 'firstValue');

        $this->_cacheController->actionClear('notExistingCache');

        $this->assertEquals('firstValue', $this->app->firstCache->get('firstKey'), 'first cache data should not be flushed');
    }

    /**
     * @expectedException \yii\console\exceptions\Exception
     */
    public function testNothingToFlushException()
    {
        $this->_cacheController->actionClear();
    }

    public function testFlushAll()
    {
        $this->container->get('firstCache')->set('firstKey', 'firstValue');
        $this->container->get('secondCache')->set('thirdKey', 'secondValue');

        $this->_cacheController->actionClearAll();

        $this->assertNull($this->container->get('firstCache')->get('firstKey'), 'first cache data should be flushed');
        $this->assertNull($this->container->get('secondCache')->get('thirdKey'), 'second cache data should be flushed');
    }
}
