<?php

namespace AssoConnect\ValidatorBundle\Test\Functional\App\Entity;

use AssoConnect\ValidatorBundle\Validator\Constraints as AssoConnectAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @AssoConnectAssert\Entity()
 * @ORM\Entity()
 */
class MyEntityParent
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="MyEntity")
     */
    public $mainChild;
}
