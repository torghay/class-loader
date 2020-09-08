<?php

namespace Torghay\ClassLoader;

use Composer\Autoload\ClassLoader as ComposerClassLoader;

class ClassLoader
{
    public static $classMap = array();

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected $composerClassLoader;

    public function __construct(ComposerClassLoader $classLoader)
    {
        $this->setComposerClassLoader($classLoader);
        
        if (!empty(self::$classMap)) {
            $classLoader->addClassMap(self::$classMap);
        }
    }

    public function loadClass(string $class): void
    {
        $path = $this->locateFile($class);

        if ($path) {
            include $path;
        }
    }

    public static function init(): void
    {
        $loaders = spl_autoload_functions();

        // Proxy the composer class loader
        foreach ($loaders as &$loader) {
            $unregisterLoader = $loader;
            if (is_array($loader) && $loader[0] instanceof ComposerClassLoader) {
                /** @var ComposerClassLoader $composerClassLoader */
                $composerClassLoader = $loader[0];
                $loader[0] = new static($composerClassLoader);
            }
            spl_autoload_unregister($unregisterLoader);
        }

        unset($loader);

        // Re-register the loaders
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

    protected function locateFile(string $className): ?string
    {
        $file = $this->getComposerClassLoader()->findFile($className);

        return is_string($file) ? $file : null;
    }
}