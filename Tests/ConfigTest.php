<?php

namespace Tests;

use App\Services\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

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
        // make sure file exists
        $path = 
        if(file_exists(Config::)) {

        }

        Config::load();
    }
//
//    public function testSave()
//    {
//    }
//

}
