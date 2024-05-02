<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchRna;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FrenchRnaProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FrenchSiretProvider;

class FrenchSiretProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new FrenchSiretProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'frenchSiret'], []];
    }
}
