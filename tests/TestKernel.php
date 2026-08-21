<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests;

use AssoConnect\ValidatorBundle\Test\Functional\App\TestKernel as FunctionalAppKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class TestKernel extends FunctionalAppKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        parent::registerContainerConfiguration($loader);
        $loader->load(__DIR__ . '/config/config.yml');
    }
}
