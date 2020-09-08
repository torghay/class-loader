<?php

namespace Torghay\ClassLoader\Bridge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Torghay\ClassLoader\Bridge\Symfony\Component\Debug\DebugClassLoader;
use Torghay\ClassLoader\ClassLoader;

class ClassLoaderBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        if (!$this->container->hasParameter('classmap')) {
            return;
        }

        if (!is_array($classMap = $this->container->getParameter('classmap'))) {
            return;
        }

        if ('prod' == $this->container->get('kernel')->getEnvironment()) {
            ClassLoader::$classMap = $classMap;
            ClassLoader::init();

            return;
        }

        if ('dev' == $this->container->get('kernel')->getEnvironment()) {
            DebugClassLoader::$classMap = $classMap;
            DebugClassLoader::enable();

            return;
        }
    }
}