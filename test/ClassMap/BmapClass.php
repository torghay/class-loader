<?php

namespace Test\Fixtures;

class BClass
{
    public static $text = 'B Map Class';

    public function test()
    {
        return self::$text;
    }
}
