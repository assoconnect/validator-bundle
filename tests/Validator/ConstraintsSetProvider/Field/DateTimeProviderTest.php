<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\DateTimeProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use DateTime;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new DateTimeProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'date'],
            [new Type(DateTime::class)],
        ];

        yield [
            ['type' => 'datetime'],
            [new Type(DateTime::class)],
        ];

        yield [
            ['type' => 'datetimetz'],
            [new Type(DateTime::class)],
        ];

        yield [
            ['type' => 'datetimeutc'],
            [new Type(DateTime::class)],
        ];
    }
}
