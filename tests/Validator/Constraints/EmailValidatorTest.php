<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use DG\BypassFinals;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Pdp\Storage\PublicSuffixListPsr18Client;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailValidatorTest extends ConstraintValidatorTestCase
{
    public function setUp(): void
    {
        BypassFinals::enable();
        parent::setUp();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        $publicSuffixListClient = new PublicSuffixListPsr18Client(
            new Client(),
            new HttpFactory()
        );
        return new EmailValidator($publicSuffixListClient);
    }

    public function getConstraint(): Constraint
    {
        return new Email([
            'message' => 'myMessage',
            'tldMessage' => 'myTldMessage',
            'dnsMessage' => 'myDnsMessage',
            'checkDNS'  => true,
        ]);
    }

    public function testValidateUnknownConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('email', new NotNull());
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Email());
        self::assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new Email());
        self::assertNoViolation();
    }
    public function providerInvalidValues(): iterable
    {
        yield [
            'format',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '"format"'],
        ];

        yield [
            'format;format@mail.com',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '"format;format@mail.com"'],
        ];

        yield [
            'mailto:format@mail.com',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '"mailto:format@mail.com"'],
        ];

        yield 'leading space' => [
            ' format@mail.com',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '" format@mail.com"'],
        ];

        yield 'trailing space' => [
            'format@mail.com ',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '"format@mail.com "'],
        ];

        yield 'trailing dot' => [
            'format@mail.com.',
            Email::INVALID_FORMAT_ERROR,
            'myMessage',
            ['{{ value }}' => '"format@mail.com."'],
        ];

        yield 'invalid public suffix' => [
            'tld@mail.error',
            Email::INVALID_TLD_ERROR,
            'myTldMessage',
            [
                '{{ value }}' => '"tld@mail.error"',
                '{{ domain }}' => '"mail.error"',
            ],
        ];

        yield 'no MX server' => [
            'john.doe@xn--gmail-9fa.com',
            Email::INVALID_DNS_ERROR,
            'myDnsMessage',
            [
                '{{ value }}' => '"john.doe@xn--gmail-9fa.com"',
                '{{ domain }}' => '"xn--gmail-9fa.com"',
            ],
        ];
    }

    public function providerValidValues(): iterable
    {
        yield ['valid@mail.com'];
        yield ['valid.valid@mail.com'];
        yield ['valid+valid@mail.com'];
        yield ['valid+valid@gmail.com'];
        yield ['valid@notaires.fr'];
    }

    public function testDisabledCheckDns(): void
    {
        $this->validator->validate('john.doe@xn--gmail-9fa.com', new Email([
            'message' => 'myMessage',
            'tldMessage' => 'myTldMessage',
            'dnsMessage' => 'myDnsMessage',
            'checkDNS'  =>  false
        ]));

        self::assertNoViolation();
    }
}
