<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Email as _Email;

/**
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
#[\Attribute]
class Email extends _Email
{
    public const INVALID_TLD_ERROR = 'd125a480-3efd-45dd-9a59-6058fccc4fe4';
    public const INVALID_DNS_ERROR = '5fca88ed-2534-4f78-b02d-397c111274ed';

    public string $tldMessage = 'The value {{ domain }} is not a valid domain name.';
    public string $dnsMessage = 'The domain {{ domain }} is not setup to received emails.';

    public bool $checkDNS = false;

    public function __construct(
        ?string $message = null,
        ?string $mode = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $tldMessage = null,
        ?string $dnsMessage = null,
        ?bool $checkDNS = null,
    ) {
        parent::__construct(
            message: $message,
            mode: $mode,
            normalizer: $normalizer,
            groups: $groups,
            payload: $payload,
        );
        $this->mode ??= 'strict';
        if (null !== $tldMessage) {
            $this->tldMessage = $tldMessage;
        }
        if (null !== $dnsMessage) {
            $this->dnsMessage = $dnsMessage;
        }
        if (null !== $checkDNS) {
            $this->checkDNS = $checkDNS;
        }
    }
}
