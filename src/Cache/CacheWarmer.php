<?php

namespace AssoConnect\ValidatorBundle\Cache;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Warm the cache defined in services.yaml for Pdp\Cache
 * Class CacheWarmer
 *
 * @package AssoConnect\ValidatorBundle\Cache
 */
class CacheWarmer implements CacheWarmerInterface
{
    private $cacheFolder;

    public function __construct(string $cacheFolder)
    {
        $this->cacheFolder = $cacheFolder;
    }

    public function warmUp(string $cacheDir)
    {
        $filesystem = new Filesystem();

        $filesystem->mkdir($cacheDir . DIRECTORY_SEPARATOR . $this->cacheFolder);
    }

    public function isOptional()
    {
        return false;
    }
}
