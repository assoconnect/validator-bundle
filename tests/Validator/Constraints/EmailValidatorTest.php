<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use DG\BypassFinals;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\Manager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class EmailValidatorTest extends ConstraintValidatorTestCase
{

    public function setUp(): void
    {
        BypassFinals::enable();
        parent::setUp();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        $cache = new Cache();
        $http = new CurlHttpClient();
        $manager = new Manager($cache, $http);
        return new EmailValidator($manager);
    }

    public function getConstraint($options = []): Constraint
    {
        return new Email([
            'message' => 'myMessage',
            'tldMessage' => 'myTldMessage',
            'dnsMessage' => 'myDnsMessage',
        ]);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateUnknownConstraint()
    {
        $this->validator->validate('email', new NotNull());
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Email());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Email());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider providerInvalidValue
     */
    public function testInvalidValues(string $value, string $code, string $message, array $parameters): void
    {
        $this->validator->validate($value, $this->getConstraint());

        $this->buildViolation($message)
            ->setCode($code)
            ->setParameters($parameters)
            ->assertRaised();
    }
    public function providerInvalidValue(): \Iterator
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

    /**
     * @dataProvider providerValidValue
     * @param string $value
     */
    public function testValidValues($value)
    {
        $this->validator->validate($value, $this->getConstraint());

        $this->assertNoViolation();
    }

    public function providerValidValue(): array
    {
        return [
            ['valid@mail.com'],
            ['valid.valid@mail.com'],
            ['valid+valid@mail.com'],
            ['valid+valid@gmail.com'],
            ['valid@notaires.fr'],
        ];
    }
}
