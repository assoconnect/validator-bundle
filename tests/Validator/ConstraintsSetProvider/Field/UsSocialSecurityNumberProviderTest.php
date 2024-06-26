<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\UsSocialSecurityNumberProvider;

class UsSocialSecurityNumberProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new UsSocialSecurityNumberProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'usSocialSecurityNumber'], [new UsSocialSecurityNumber()]];
    }
}
