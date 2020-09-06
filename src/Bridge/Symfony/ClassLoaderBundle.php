<?php

namespace Torghay\ClassLoader\Bridge\Symfony;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ClassLoaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        if (!$container->hasParameter('classmaps')) {
            return;
        }

        $classMaps = $container->getParameter('classmaps');
        if (empty($classMaps)) {
            return;
        }

        var_dump($classMaps);die;
    }
}