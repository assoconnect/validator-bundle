<?php

namespace AssoConnect\ValidatorBundle\Test\Functional\App\Entity;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\ValidatorBundle\Validator\Constraints as AssoConnectAssert;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;

/**
 * @AssoConnectAssert\Entity()
 * @ORM\Entity()
 */
class MyEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public int $id;

    /**
     * @ORM\Column(type="array")
     * @var array<mixed>
     */
    public array $array;

    /**
     * @ORM\Column(type="bic")
     */
    public string $bic;

    /**
     * @ORM\Column(type="bigint")
     */
    public int $bigint;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    public int $bigintUnsigned;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $boolean;

    /**
     * @ORM\Column(type="country")
     */
    public string $country;

    /**
     * @ORM\Column(type="currency")
     */
    public Currency $currency;

    /**
     * @ORM\Column(type="date")
     */
    public \DateTime $date;

    /**
     * @ORM\Column(type="datetime")
     */
    public \DateTime $datetime;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=3)
     */
    public float $decimal;

    /**
     * @ORM\Column(type="email")
     */
    public string $email;

    /**
     * @ORM\Column(type="float")
     */
    public float $float;

    /**
     * @ORM\Column(type="iban")
     */
    public string $iban;

    /**
     * @ORM\Column(type="integer")
     */
    public int $integer;

    /**
     * @ORM\Column(type="ip")
     */
    public string $ip;

    /**
     * @ORM\Column(type="json")
     */
    public string $json;

    /**
     * @ORM\Column(type="latitude")
     */
    public string $latitude;

    /**
     * @ORM\Column(type="locale")
     */
    public string $locale;

    /**
     * @ORM\Column(type="longitude")
     */
    public string $longitude;

    /**
     * @ORM\Column(type="money")
     */
    public float $money;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    public string $notNullable;

    /**
     * @ORM\Column(type="percent")
     */
    public float $percent;

    /**
     * @ORM\Column(type="phone")
     */
    public string $phone;

    /**
     * @ORM\Column(type="phonelandline")
     */
    public string $phonelandline;

    /**
     * @ORM\Column(type="phonemobile")
     */
    public string $phonemobile;

    /**
     * @ORM\Column(type="smallint")
     */
    public int $smallint;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    public int $smallintUnsigned;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public string $string;

    /**
     * @ORM\Column(type="text", length=10)
     */
    public string $text;

    /**
     * @ORM\Column(type="timezone")
     */
    public string $timezone;

    /**
     * @ORM\Column(type="uuid")
     */
    public string $uuid;

    /**
     * @ORM\Column(type="uuid_binary_ordered_time")
     */
    public string $uuid_binary_ordered_time;

    /**
     * ASSOCIATIONS
     */

    /**
     * @ORM\ManyToOne(targetEntity="MyEntityParent")
     */
    public ?MyEntityParent $parentNullable;

    /**
     * @ORM\ManyToOne(targetEntity="MyEntityParent")
     * @ORM\JoinColumn(nullable=false)
     */
    public MyEntityParent $parentNotNullable;

    /**
     * @ORM\OneToMany(targetEntity="MyEntityParent", mappedBy="mainChild")
     */
    public MyEntityParent $mainParent;

    /**
     * @ORM\ManyToMany(targetEntity="MyEntityParent")
     * @var array<MyEntityParent>
     */
    public array $parents;

    /**
     * EMBEDDABLES
     */

    /**
     * @ORM\Embedded(class="MyEmbeddable")
     */
    public MyEmbeddable $embeddable;

    /**
     * @ORM\Column(type="date_absolute")
     */
    public AbsoluteDate $absoluteDate;

    /**
     * @ORM\Column(type="frenchRna")
     */
    public string $frenchRNA;

    /**
     * @ORM\Column(type="frenchSiren")
     */
    public string $frenchSIREN;
}
