<?php

namespace Tests;

use App\Models\Config;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ConfigTest extends TestCase
{
    public const FILENAME = 'test.json';

    public $rootPath;
    public static $configPath;

    /**
     * @depends testLoad
     * @depends testClear
     */
    public function setUp(): void
    {
        $this->rootPath = realpath(__DIR__ . '/..');

        Config::setRootPath($this->rootPath);
        Config::setConfigFilename(self::FILENAME);

        self::$configPath = $this->getConfigPath();
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$configPath);
    }

//    public function testSetRootPath()
//    {
//
//    }
//
//    public function testSetConfigFilename()
//    {
//
//    }

    public function testSetAndGet()
    {
        $name = 'Anton';

        Config::set('name', $name);
        $configName = Config::get('name');

        $this->assertEquals($configName, $name);
    }

    public function testGetEmpty()
    {
        $randomVar = 'randomVar_' . time();
        $configName = Config::get($randomVar, 'xxx');

        $this->assertEquals($configName, 'xxx');
    }

    public function testLoad()
    {
        $content = null;
        if(file_exists(self::$configPath)) {
            $this->assertNotFalse(Config::load());
        } else {
            $key = 'randomVar_' . time();
            $val = random_bytes(8);
            Config::set($key, $val);
            Config::save();

            $this->assertNotFalse(Config::load());
        }
    }

    public function testClear()
    {
        $key = 'randomVar_' . time();
        $val = random_bytes(8);
        Config::set($key, $val);

        Config::clear();

        $this->assertFalse(Config::has($key));
    }


    public function testHas()
    {
        $key = 'randomVar_' . time();
        $val = random_bytes(8);
        Config::set($key, $val);

        $this->assertTrue(Config::has($key));
    }

    /**
     * @depends testLoad
     * @depends testClear
     */
    public function testSave()
    {
        $key = 'randomVar_' . time();
        $val = substr(str_shuffle(MD5(microtime())), 0, 10);

        Config::set($key, $val);
        Config::save();

        Config::clear();
        Config::load();

        $this->assertEquals($val, Config::get($key));
    }

    protected function getConfigPath() {
        $protectedPath = new ReflectionMethod(
          new Config, 'getConfigPath'
        );

        $protectedPath->setAccessible(true);
        return $protectedPath->invoke(new Config);
    }
}
