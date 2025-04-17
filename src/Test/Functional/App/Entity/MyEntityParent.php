<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test\Functional\App\Entity;

use AssoConnect\ValidatorBundle\Validator\Constraints as AssoConnectAssert;
use Doctrine\ORM\Mapping as ORM;

#[AssoConnectAssert\Entity]
#[ORM\Entity]
class MyEntityParent
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: MyEntity::class)]
    public MyEntity $mainChild;
}
