<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\PhoneProvider;

class PhoneProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new PhoneProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'phone'],
            [new Phone()],
        ];
    }
}
