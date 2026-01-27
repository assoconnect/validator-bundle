<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test\Functional\App;

use AssoConnect\ValidatorBundle\AssoConnectValidatorBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new AssoConnectValidatorBundle();
        yield new DoctrineBundle();
    }

    public function getCacheDir(): string
    {
        return $this->basePath() . 'cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->basePath() . 'logs';
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    private function basePath(): string
    {
        return sys_get_temp_dir() . '/AssoConnectValidatorBundle/' . Kernel::VERSION . '/';
    }
}
