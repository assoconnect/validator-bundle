<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Functional;

use AssoConnect\ValidatorBundle\Cache\PublicSuffixListCacheWarmer;
use AssoConnect\ValidatorBundle\Tests\Stub\PublicSuffixListClientStub;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use Pdp\Storage\RulesStorage;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PublicSuffixListCacheChainTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        restore_exception_handler();
    }

    public function testCacheWarmerRunsTheWholePsr16CacheChain(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $rulesStorage = $container->get(RulesStorage::class);
        self::assertInstanceOf(RulesStorage::class, $rulesStorage);
        $rulesStorage->delete(EmailValidator::PUBLIC_SUFFIX_LIST_URI);

        $stub = $container->get(PublicSuffixListClientStub::class);
        self::assertInstanceOf(PublicSuffixListClientStub::class, $stub);

        $warmer = $container->get(PublicSuffixListCacheWarmer::class);
        self::assertInstanceOf(PublicSuffixListCacheWarmer::class, $warmer);
        self::assertFalse($warmer->isOptional());

        $cacheDir = self::$kernel instanceof \Symfony\Component\HttpKernel\KernelInterface
            ? self::$kernel->getCacheDir()
            : sys_get_temp_dir();

        // The kernel boot may already have run the warmer, depending on the Symfony version
        $requestsBeforeWarmup = $stub->getRequestCount();

        self::assertSame([], $warmer->warmUp($cacheDir));
        self::assertSame($requestsBeforeWarmup + 1, $stub->getRequestCount());

        self::assertSame([], $warmer->warmUp($cacheDir));
        self::assertSame(
            $requestsBeforeWarmup + 1,
            $stub->getRequestCount(),
            'The second warm-up must be served from the PSR-16 cache'
        );

        self::assertTrue(interface_exists(CacheInterface::class, false));
    }
}
