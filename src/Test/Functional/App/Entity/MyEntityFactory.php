<?php


namespace AssoConnect\ValidatorBundle\Test\Functional\App\Entity;

use AssoConnect\PHPDate\AbsoluteDate;
use Doctrine\ORM\Mapping\Embeddable;

class MyEntityFactory
{
    public const ID = 54;
    public const BIC = "CCBPFRPPVER";
    public const BIGINT = -54000000;
    public const BIGINT_UNSIGNED = 5400000;
    public const BOOLEAN = true;
    public const COUNTRY = "FR";
    public const CURRENCY = "EUR";
    public const DATE = '2020-01-01';
    public const DATETIME = '2020-01-01 00:00:00';
    public const DECIMAL = 54.104;
    public const EMAIL = "test@assoconnect.com";
    public const FLOAT = 54.104;
    public const IBAN = "FR1420041010050500013M02606";
    public const INTEGER = 54;
    public const IP = "207.142.131.245";
    public const JSON = "{test}";
    public const LATITUDE = 54;
    public const LOCALE = "fr";
    public const LONGITUDE = 54;
    public const MONEY = 54;
    public const PERCENT = 54;
    public const PHONE = "+33123456789";
    public const PHONE_LANDLINE = "+33123456789";
    public const PHONE_MOBILE = "+33623456789";
    public const POSTAL = "75001";
    public const SMALLINT = -4;
    public const SMALLINT_UNSIGNED = 4;
    public const STRING = "string";
    public const TEXT = "Loremipsum";
    public const TIMEZONE = "Europe/Paris";
    public const UUID = "123e4567-e89b-12d3-a456-426614174000";
    public const UUID_BINARY_ORDERED_TIME = "123e4567-e89b-12d3-a456-426614174000";

    public static function createForTest(): MyEntity
    {
        $entity = new MyEntity();
        $entity->id = self::ID;
        $entity->absoluteDate = new AbsoluteDate(self::DATE);
        $entity->bic = self::BIC;
        $entity->bigint = self::BIGINT;
        $entity->bigintUnsigned = self::BIGINT_UNSIGNED;
        $entity->boolean = self::BOOLEAN;
        $entity->country = self::COUNTRY;
        $entity->currency = self::CURRENCY;
        $entity->date = new \DateTime(self::DATE);
        $entity->datetime = new \DateTime(self::DATETIME);
        $entity->decimal = self::DECIMAL;
        $entity->email = self::EMAIL;
        $entity->float = self::FLOAT;
        $entity->iban = self::IBAN;
        $entity->integer = self::INTEGER;
        $entity->ip = self::IP;
        $entity->json = self::JSON;
        $entity->latitude = self::LATITUDE;
        $entity->locale = self::LOCALE;
        $entity->longitude = self::LONGITUDE;
        $entity->money = self::MONEY;
        $entity->notNullable = "anything";
        $entity->percent = self::PERCENT;
        $entity->phone = self::PHONE;
        $entity->phonelandline = self::PHONE_LANDLINE;
        $entity->phonemobile = self::PHONE_MOBILE;
        $entity->postal = self::POSTAL;
        $entity->smallint = self::SMALLINT;
        $entity->smallintUnsigned = self::SMALLINT_UNSIGNED;
        $entity->string = self::STRING;
        $entity->text = self::TEXT;
        $entity->timezone = self::TIMEZONE;
        $entity->uuid = self::UUID;
        $entity->uuid_binary_ordered_time = self::UUID_BINARY_ORDERED_TIME;

        $embeddable = new MyEmbeddable(self::BOOLEAN);

        $entity->embeddable = $embeddable;

        $parent = new MyEntityParent();

        $entity->parentNotNullable = $parent;

        return $entity;
    }
}
