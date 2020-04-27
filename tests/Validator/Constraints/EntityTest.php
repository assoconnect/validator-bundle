<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testGetTargets()
    {
        $entity = new Entity();
        $this->assertSame('class', $entity->getTargets());
    }
}
