<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testGetTargets(): void
    {
        $entity = new Entity();
        self::assertSame('class', $entity->getTargets());
    }
}
