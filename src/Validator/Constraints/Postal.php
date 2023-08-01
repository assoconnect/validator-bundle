<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Postal extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'ea4eb1eb-3984-4455-9b00-dcbf5221f933';

    public const MISSING_ERROR = '799ee5b5-5cc6-4d85-abd3-8efdfd536f29';

    public const NO_POSTAL_CODE_SYSTEM = '4c07fa49-108a-49af-9983-f547ee477351';

    public const UNKNOWN_COUNTRY_ERROR = 'd3710953-d14c-444f-bc7d-8d1a45d7e413';

    public string $countryPropertyPath;

    public function getDefaultOption(): string
    {
        return 'countryPropertyPath';
    }

    public function getRequiredOptions(): array
    {
        return ['countryPropertyPath'];
    }

    public string $message = 'The value {{ value }} is not a valid postal code.';
    public string $unknownCountryMessage = 'The country {{ country }} is unknown.';
    public string $missingPostalCodeMessage = 'The postal code must be provided.';
    public string $noPostalCodeMessage = 'There is no postal code system in the country {{ country }}.';
}
