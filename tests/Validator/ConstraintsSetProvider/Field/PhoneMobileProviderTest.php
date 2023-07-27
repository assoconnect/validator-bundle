<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\PhoneMobileProvider;

class PhoneMobileProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new PhoneMobileProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'phonemobile'],
            [new PhoneMobile()],
        ];
    }
}
