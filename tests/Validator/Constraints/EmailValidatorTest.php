<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use DG\BypassFinals;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\Domain;
use Pdp\Manager;
use Pdp\Rules;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;

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
        return new Email(['message' => 'myMessage', 'TLDMessage' => 'myMessage']);
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
     * @param string $value
     * @param string $code
     */
    public function testInvalidValues($value, $code)
    {
        $this->validator->validate($value, $this->getConstraint());

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode($code)
            ->assertRaised()
        ;
    }
    public function providerInvalidValue(): array
    {
        return [
            // Format
            // #0
            [
                'format',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #1
            [
                'format;format@mail.com',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #2
            [
                'mailto:format@mail.com',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #3
            [
                ' format@mail.com',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #4
            [
                'format@mail.com ',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #5
            [
                'format@mail.com.',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #6 TLD
            [
                'tld@mail.error',
                Email::INVALID_TLD_ERROR,
            ],
            // #7 DNS
            [
                'john.doe@xn--gmail-9fa.com',
                Email::INVALID_TLD_ERROR,
            ]

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
