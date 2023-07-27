<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\LocaleProvider;
use Symfony\Component\Validator\Constraints\Locale;

class LocaleProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new LocaleProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'locale'],
            [new Locale(['canonicalize' => true])],
        ];
    }
}
