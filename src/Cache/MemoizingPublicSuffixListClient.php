<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Cache;

use Pdp\PublicSuffixList;
use Pdp\Storage\PublicSuffixListClient;

/**
 * Keeps the hydrated PublicSuffixList in memory so repeated lookups in the same
 * process do not unserialize the list from the PSR-16 cache on every call.
 */
final class MemoizingPublicSuffixListClient implements PublicSuffixListClient
{
    /** @var array<string, PublicSuffixList> */
    private array $cache = [];

    public function __construct(private readonly PublicSuffixListClient $decorated)
    {
    }

    public function get(string $uri): PublicSuffixList
    {
        if (!isset($this->cache[$uri])) {
            $this->cache[$uri] = $this->decorated->get($uri);
        }

        return $this->cache[$uri];
    }
}
