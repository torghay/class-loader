<?php

namespace Torghay\ClassLoader\Bridge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Torghay\ClassLoader\ClassLoader;

class ClassLoaderBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        if (!$this->container->hasParameter('classmap')) {
            return;
        }

        $classMap = $this->container->getParameter('classmap');
        if (empty($classMap)) {
            return;
        }

        ClassLoader::$classMap = $classMap;
        ClassLoader::init();
    }
}