<?php

namespace AssoConnect\ValidatorBundle\Test;

// TODO : When doctrine validator bundle is cleaned, fix import
//use AssoConnect\DoctrineValidatorBundle\AssoConnectDoctrineValidatorBundle;
use AssoConnect\ValidatorBundle\AssoConnectValidatorBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
        //            new AssoConnectDoctrineValidatorBundle(),
            new AssoConnectValidatorBundle(),
            new DoctrineBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}
