<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\TextProvider;
use Symfony\Component\Validator\Constraints\Length;

class TextProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new TextProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'text'],
            [new Length(['max' => 65535, 'charset' => '8bit'])],
        ];

        yield [
            ['type' => 'text', 'length' => 1000],
            [new Length(['max' => 1000, 'charset' => '8bit'])],
        ];
    }
}
