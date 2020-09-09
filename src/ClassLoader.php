<?php

namespace Torghay\ClassLoader;

use Composer\Autoload\ClassLoader as ComposerClassLoader;

class ClassLoader
{
    public static $classMap = array();

    /**
     * @var ComposerClassLoader
     */
    protected $composerClassLoader;

    public function __construct(ComposerClassLoader $classLoader)
    {
        $this->setComposerClassLoader($classLoader);

        if (!empty(self::$classMap)) {
            $classLoader->addClassMap(self::$classMap);
        }
    }

    public function loadClass(string $class)
    {
        $path = $this->locateFile($class);

        if ($path) {
            include $path;
        }
    }

    public static function init()
    {
        $loaders = spl_autoload_functions();

        foreach ($loaders as &$loader) {
            $unregisterLoader = $loader;
            if (is_array($loader) && $loader[0] instanceof ComposerClassLoader) {
                $composerClassLoader = $loader[0];
                $loader[0] = new static($composerClassLoader);
            }
            spl_autoload_unregister($unregisterLoader);
        }

        unset($loader);

        foreach ($loaders as $loader) {
            spl_autoload_register($loader, true, false);
        }
    }

    public function setComposerClassLoader(ComposerClassLoader $classLoader): self
    {
        $this->composerClassLoader = $classLoader;

        return $this;
    }

    public function getComposerClassLoader(): ComposerClassLoader
    {
        return $this->composerClassLoader;
    }

    protected function locateFile(string $className)
    {
        $file = $this->getComposerClassLoader()->findFile($className);

        return is_string($file) ? $file : null;
    }
}