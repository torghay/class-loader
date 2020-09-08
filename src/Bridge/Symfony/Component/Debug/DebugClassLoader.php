<?php

namespace Torghay\ClassLoader\Bridge\Symfony\Component\Debug;

use Symfony\Component\Debug\DebugClassLoader as SymfonyDebugClassLoader;
use Composer\Autoload\ClassLoader;
use function is_array;

class DebugClassLoader extends SymfonyDebugClassLoader
{
    public static $classMap = array();

    public function __construct(callable $classLoader)
    {
        parent::__construct($classLoader[0]->getClassLoader());

        if (!empty(self::$classMap) && is_array($this->getClassLoader()) && $this->getClassLoader()[0] instanceof ClassLoader) {
            $this->getClassLoader()[0]->addClassMap(self::$classMap);
        }
    }

    /**
     * Wraps all autoloaders.
     */
    public static function enable()
    {
        // Ensures we don't hit https://bugs.php.net/42098
        class_exists('Symfony\Component\Debug\ErrorHandler');
        class_exists('Psr\Log\LogLevel');

        if (!is_array($functions = spl_autoload_functions())) {
            return;
        }

        foreach ($functions as $function) {
            spl_autoload_unregister($function);
        }

        foreach ($functions as $function) {
            if (!is_array($function) || !$function[0] instanceof self) {
                $function = [new static($function), 'loadClass'];
            }
            spl_autoload_register($function);
        }
    }
}