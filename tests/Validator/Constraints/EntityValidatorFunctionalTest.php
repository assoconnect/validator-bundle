<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntity;
use AssoConnect\ValidatorBundle\Tests\Entity\EntityTest;
use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityValidatorFunctionalTest extends KernelTestCase
{
    /** @var ValidatorInterface */
    protected $validator;

    public function setUp(): void
    {
        self::bootKernel();

        $this->validator = self::$container->get(ValidatorInterface::class);
    }

    public function testNoViolationRaisedWithValidDoctrineEntity()
    {
        $entity = new MyEntity();
        $entity->postal = "59270";
        $entity->country = "FR";

        $violations = $this->validator->validate($entity, new Entity());
        $this->assertCount(0, $violations);
    }

    public function testViolationRaisedWithInvalidDoctrineEntity()
    {
        $entity = new MyEntity();
        $entity->postal = "abcdef";
        $entity->country = "FR";

        $violations = $this->validator->validate($entity, new Entity());

        dump($violations);
    }
}
