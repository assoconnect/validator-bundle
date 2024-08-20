<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\StringProvider;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;

class StringProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new StringProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'string'],
            [new Length(['max' => 255]), new NoSuspiciousCharacters()],
        ];

        yield [
            [
                'type' => 'string',
                'length' => 10,
            ],
            [new Length(['max' => 10]), new NoSuspiciousCharacters()],
        ];

        yield [
            [
                'type' => 'string',
                'nullable' => true,
            ],
            [
                new Length(['max' => 255]),
                new NoSuspiciousCharacters(),
                new NotBlank(['allowNull' => true]),
            ],
        ];
    }
}
