<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Cache;

use AssoConnect\ValidatorBundle\Cache\MemoizingPublicSuffixListClient;
use Pdp\PublicSuffixList;
use Pdp\Storage\PublicSuffixListClient;
use PHPUnit\Framework\TestCase;

class MemoizingPublicSuffixListClientTest extends TestCase
{
    public function testGetMemoizesResultForSameUri(): void
    {
        $publicSuffixList = self::createStub(PublicSuffixList::class);

        $decorated = $this->createMock(PublicSuffixListClient::class);
        $decorated->expects(self::once())
            ->method('get')
            ->with('https://example.com/public_suffix_list.dat')
            ->willReturn($publicSuffixList);

        $memoizingPublicSuffixListClient = new MemoizingPublicSuffixListClient($decorated);

        $firstResult = $memoizingPublicSuffixListClient->get('https://example.com/public_suffix_list.dat');
        $secondResult = $memoizingPublicSuffixListClient->get('https://example.com/public_suffix_list.dat');

        self::assertSame($publicSuffixList, $firstResult);
        self::assertSame($publicSuffixList, $secondResult);
    }
}
