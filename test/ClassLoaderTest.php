<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\AClass;
use Test\Fixtures\BClass;
use Torghay\ClassLoader\ClassLoader;

class ClassLoaderTest extends TestCase
{
    public function testInit()
    {
        ClassLoader::$classMap = [
            'Test\Fixtures\AClass' => BASE_PATH.'/test/ClassMap/AmapClass.php',
            'Test\Fixtures\BClass' => BASE_PATH.'/test/ClassMap/BmapClass.php',
        ];
        ClassLoader::init();

        self::assertEquals('A Map Class', (new AClass())->test());
        self::assertEquals('B Map Class', (new BClass())->test());
    }
}
