<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\DutchKvkNumber;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\DutchKvkNumberProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;

class DutchKvkNumberProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new DutchKvkNumberProvider();
    }

    public static function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'dutchKvkNumber'], [new DutchKvkNumber()]];
    }
}
