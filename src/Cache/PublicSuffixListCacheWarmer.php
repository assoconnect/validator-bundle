<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Cache;

use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use Pdp\Storage\RulesStorage;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Warm the cache defined in services.yaml for the EmailValidator
 */
class PublicSuffixListCacheWarmer implements CacheWarmerInterface
{
    private RulesStorage $rulesStorage;

    public function __construct(RulesStorage $rulesStorage)
    {
        $this->rulesStorage = $rulesStorage;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        // This call is done to load the IANA database into the cache
        $this->rulesStorage->get(EmailValidator::PUBLIC_SUFFIX_LIST_URI);

        return [];
    }

    public function isOptional(): bool
    {
        return false;
    }
}
