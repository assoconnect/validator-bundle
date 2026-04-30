<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\SpanishNif;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\SpanishNifProvider;

class SpanishNifProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new SpanishNifProvider();
    }

    public static function getConstraintsForTypeProvider(): iterable
    {
        yield [['type' => 'spanishNif'], [new SpanishNif()]];
    }
}
