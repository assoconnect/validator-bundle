<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Exception\UnsupportedIntegerTypeException;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @link https://dev.mysql.com/doc/refman/8.0/en/integer-types.html
 */
class IntegerProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return in_array(
            $type,
            [
                Types::SMALLINT,
                Types::INTEGER,
                Types::BIGINT,
            ],
            true
        );
    }

    public function getConstraints(array $fieldMapping): array
    {
        $isSigned = !isset($fieldMapping['options']['unsigned']) || false === $fieldMapping['options']['unsigned'];

        return [
            new Type('integer'),
            new GreaterThanOrEqual($this->getMinValue($fieldMapping['type'], $isSigned)),
            new LessThanOrEqual($this->getMaxValue($fieldMapping['type'], $isSigned)),

        ];
    }

    /** @return int|float */
    private function getMinValue(string $type, bool $isSigned)
    {
        if ($isSigned) {
            switch ($type) {
                case Types::SMALLINT:
                    return -32768;
                case Types::INTEGER:
                    return -2147483648;
                case Types::BIGINT:
                    return -9223372036854775808;
                default:
                    throw new UnsupportedIntegerTypeException($type);
            }
        }

        return 0;
    }

    /** @return int|float */
    private function getMaxValue(string $type, bool $isSigned)
    {
        if ($isSigned) {
            switch ($type) {
                case Types::SMALLINT:
                    return 32767;
                case Types::INTEGER:
                    return 2147483647;
                case Types::BIGINT:
                    return 9223372036854775807;
                default:
                    throw new UnsupportedIntegerTypeException($type);
            }
        }

        switch ($type) {
            case Types::SMALLINT:
                return 65535;
            case Types::INTEGER:
                return 4294967295;
            case Types::BIGINT:
                return 18446744073709551615;
            default:
                throw new UnsupportedIntegerTypeException($type);
        }
    }
}
