<?php

namespace AssoConnect\ValidatorBundle\Test\Functional\App;

use AssoConnect\ValidatorBundle\AssoConnectValidatorBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new AssoConnectValidatorBundle(),
            new DoctrineBundle(),
        ];
    }

    public function getCacheDir()
    {
        return $this->basePath() . 'cache/' . $this->environment;
    }

    public function getLogDir()
    {
        return $this->basePath() . 'logs';
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    private function basePath()
    {
        return sys_get_temp_dir() . '/AssoConnectValidatorBundle/' . Kernel::VERSION . '/';
    }
}
