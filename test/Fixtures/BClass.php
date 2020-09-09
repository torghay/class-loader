<?php

namespace Test\Fixtures;

class BClass
{
    public static $text = 'A Class';

    public function test()
    {
        return self::$text;
    }
}
