<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\LastDigitsUsSocialSecurityNumber;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\LastDigitsUsSocialSecurityNumberProvider;

class LastDigitsUsSocialSecurityNumberProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new LastDigitsUsSocialSecurityNumberProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'lastDigitsUsSocialSecurityNumber'], [new LastDigitsUsSocialSecurityNumber()]];
    }
}
