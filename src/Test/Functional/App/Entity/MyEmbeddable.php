<?php

declare(strict_types=1);

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
    public bool $bool;

    public function __construct(bool $bool)
    {
        $this->bool = $bool;
    }
}
