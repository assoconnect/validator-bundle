<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\DateTimeImmutableMicroSecondsType;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\DateTimeImmutableProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeImmutableProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new DateTimeImmutableProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'date_immutable'],
            [new Type(DateTimeImmutable::class)],
        ];

        yield [
            ['type' => 'datetime_immutable'],
            [new Type(DateTimeImmutable::class)],
        ];

        yield [
            ['type' => 'datetimetz_immutable'],
            [new Type(DateTimeImmutable::class)],
        ];

        yield [
            ['type' => DateTimeImmutableMicroSecondsType::NAME],
            [new Type(DateTimeImmutable::class)],
        ];
    }
}
