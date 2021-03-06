<?php

namespace AssoConnect\ValidatorBundle\Test\Functional\App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class MyEmbeddable
{

    /**
     * @ORM\Column(type="boolean")
     */
    public $bool;

    public function __construct($bool)
    {
        $this->bool = $bool;
    }
}
