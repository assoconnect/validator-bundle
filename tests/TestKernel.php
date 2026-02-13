<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /** @return list<\Symfony\Component\HttpKernel\Bundle\BundleInterface> */
    public function registerBundles(): iterable
    {
        return [new FrameworkBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}
