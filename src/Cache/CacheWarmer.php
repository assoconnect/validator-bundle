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
    private string $cacheFolder;

    public function __construct(string $cacheFolder)
    {
        $this->cacheFolder = $cacheFolder;
    }

    public function warmUp(string $cacheDir): array
    {
        $filesystem = new Filesystem();

        $filesystem->mkdir($dir = $cacheDir . DIRECTORY_SEPARATOR . $this->cacheFolder);

        return [$dir];
    }

    public function isOptional(): bool
    {
        return false;
    }
}
