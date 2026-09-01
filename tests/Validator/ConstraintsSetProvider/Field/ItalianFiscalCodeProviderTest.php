<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\ItalianFiscalCode;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\ItalianFiscalCodeProvider;

class ItalianFiscalCodeProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new ItalianFiscalCodeProvider();
    }

    public static function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'italianFiscalCode'], [new ItalianFiscalCode()]];
    }
}
