<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\BelgianEnterpriseNumber;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\BelgianEnterpriseNumberProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;

class BelgianEnterpriseNumberProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new BelgianEnterpriseNumberProvider();
    }

    public static function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'belgianEnterpriseNumber'], [new BelgianEnterpriseNumber()]];
    }
}
