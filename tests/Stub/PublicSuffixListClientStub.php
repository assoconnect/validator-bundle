<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Stub;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PublicSuffixListClientStub implements ClientInterface
{
    private int $requestCount = 0;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requestCount++;

        return new Response(200, [], "// public suffix list fixture\ncom\n");
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }
}
