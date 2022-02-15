<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Validates whether a value is a valid locale code.
 */
class PostalValidator extends ConstraintValidator
{
    private const THREE_DIGIT_FORMAT = '^[0-9]{3}$';
    private const FOUR_DIGIT_FORMAT = '^[0-9]{4}$';
    private const FIVE_DIGIT_FORMAT = '^[0-9]{5}$';
    private const SIX_DIGIT_FORMAT = '^[0-9]{6}$';
    private const FIVE_FIVE_FOUR_DIGIT_FORMAT = '^([0-9]{5}|[0-9]{5}-[0-9]{4})$';

    /**
     * @link https://en.wikipedia.org/wiki/List_of_postal_codes
     */
    private const POSTALS = [
        null => null,
        'AD' => '^AD[0-9]{3}$', // Andora
        'AE' => null, // United Arab Emirates
        'AF' => self::FOUR_DIGIT_FORMAT, // Afghanistan
        'AG' => null, // Antigua and Barbuda
        'AL' => self::FOUR_DIGIT_FORMAT, // Albania
        'AI' => '^AI-2640$', // Anguilla
        'AM' => self::FOUR_DIGIT_FORMAT, // Armenia
        'AO' => null, // Angola
        'AQ' => '^BIQQ 1ZZ$', // Antarctica
        'AR' => self::FOUR_DIGIT_FORMAT, // Argentina
        'AS' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // American Samoa
        'AT' => self::FOUR_DIGIT_FORMAT, // Austria
        'AU' => self::FOUR_DIGIT_FORMAT, // Australia
        'AW' => null, // Aruba
        'AX' => self::FIVE_DIGIT_FORMAT, // Åland (Finlande territory)
        'AZ' => '^AZ[0-9]{4}$', // Azerbaijan
        'BA' => self::FIVE_DIGIT_FORMAT, // Bosnia and Herzegovina
        'BB' => '^BB[0-9]{5}$', // Barbados
        'BD' => self::FOUR_DIGIT_FORMAT, // Bangladesh
        'BE' => '^(B-)?[0-9]{4}$', // Belgium
        'BF' => null, // Burkina Faso
        'BG' => self::FOUR_DIGIT_FORMAT, // Bulgaria
        'BH' => '^([0-9]{3}|[0-9]{4})$', // Bahrain
        'BI' => null, // Burundi
        'BJ' => null, // Benin
        'BL' => '^97133$', // Saint Barthélemy
        'BM' => null, // Bermuda
        'BN' => '^[A-Z]{2}[0-9]{4}$', // Brunei Darussalam
        'BO' => null, // Bolivia (Plurinational State of)
        'BQ' => null, // Bonaire, Sint Eustatius and Saba
        'BR' => '^([0-9]{5}|[0-9]{5}-[0-9]{3})$', // Brazil
        'BS' => null, // Bahamas
        'BT' => self::FIVE_DIGIT_FORMAT, // Bhutan
        'BV' => null, // Bouvet Island
        'BW' => null, // Botswana
        'BY' => self::SIX_DIGIT_FORMAT, // Belarus
        'BZ' => null, // Belize
        'CA' => '^[A-Z][0-9][A-Z]( [0-9][A-Z][0-9])?$', // Canada
        'CC' => self::FOUR_DIGIT_FORMAT, // Cocos (Keeling) Islands
        'CD' => null, // Congo (Democratic Republic of the)
        'CF' => null, // Central African Republic
        'CG' => null, // Congo
        'CH' => self::FOUR_DIGIT_FORMAT, // Switzerland
        'CI' => null, // Côte d'Ivoire
        'CK' => null, // Cook Islands
        'CL' => '^([0-9]{7}|[0-9]{3}-[0-9]{4})$', // Chile
        'CM' => null, // Cameroon
        'CN' => self::SIX_DIGIT_FORMAT, // China
        'CO' => self::SIX_DIGIT_FORMAT, // Colombia
        'CR' => self::FIVE_DIGIT_FORMAT, // Costa Rica
        'CU' => self::FIVE_DIGIT_FORMAT, // Cuba
        'CV' => self::FOUR_DIGIT_FORMAT, // Cabo Verde
        'CW' => null, // Curaçao
        'CX' => self::FOUR_DIGIT_FORMAT, // Christmas Island
        'CY' => self::FOUR_DIGIT_FORMAT, // Cyprus
        'CZ' => '^[0-9]{3} [0-9]{2}$', // Czech Republic
        'DE' => self::FIVE_DIGIT_FORMAT, // Germany
        'DJ' => null, // Djibouti
        'DK' => self::FOUR_DIGIT_FORMAT, // Denmark
        'DM' => null, // Dominica
        'DO' => self::FIVE_DIGIT_FORMAT, // Dominican Republic
        'DZ' => self::FIVE_DIGIT_FORMAT, // Algeria
        'EC' => self::SIX_DIGIT_FORMAT, // Ecuador
        'EE' => self::FIVE_DIGIT_FORMAT, // Estonia
        'EG' => self::FIVE_DIGIT_FORMAT, // Egypt
        'EH' => null, // Western Sahara
        'ER' => null, // Eritrea
        'ES' => self::FIVE_DIGIT_FORMAT, // Spain
        'ET' => self::FOUR_DIGIT_FORMAT, // Ethiopia
        'FI' => self::FIVE_DIGIT_FORMAT, // Finland
        'FJ' => null, // Fiji
        'FK' => '^FIQQ 1ZZ$', // Falkland Islands (Malvinas)
        'FM' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Micronesia (Federated States of)
        'FO' => self::THREE_DIGIT_FORMAT, // Faroe Islands
        'FR' => self::FIVE_DIGIT_FORMAT, // France
        'GA' => null, // Gabon
        'GB' => '^[A-Z]([A-Z][0-9][A-Z]|[0-9][A-Z]|[0-9]{1,2}|[A-Z][0-9]{1,2})( [0-9]{1}[A-Z]{2})?$',
        // United Kingdom of Great Britain and Northern Ireland
        'GD' => null, // Grenada
        'GE' => self::FOUR_DIGIT_FORMAT, // Georgia
        'GF' => '^973[0-9]{2}$', // French Guiana
        'GG' => '^GY[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Guernsey
        'GH' => null, // Ghana
        'GI' => '^GX11 1AA$', // Gibraltar
        'GL' => self::FOUR_DIGIT_FORMAT, // Greenland
        'GM' => null, // Gambia
        'GN' => self::THREE_DIGIT_FORMAT, // Guinea
        'GP' => '^971[0-9]{2}$', // Guadeloupe
        'GQ' => null, // Equatorial Guinea
        'GR' => '^[0-9]{3} [0-9]{2}$', // Greece
        'GS' => '^SIQQ 1ZZ$', // South Georgia and the South Sandwich Islands
        'GT' => self::FIVE_DIGIT_FORMAT, // Guatemala
        'GU' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Guam
        'GW' => self::FOUR_DIGIT_FORMAT, // Guinea-Bissau
        'GY' => null, // Guyana
        'HK' => null, // Hong Kong
        'HM' => null, // Heard Island and McDonald Islands
        'HN' => '^HN[0-9]{5}$', // Honduras
        'HR' => self::FIVE_DIGIT_FORMAT, // Croatia
        'HT' => self::FOUR_DIGIT_FORMAT, // Haiti
        'HU' => self::FOUR_DIGIT_FORMAT, // Hungary
        'ID' => self::FIVE_DIGIT_FORMAT, // Indonesia
        'IE' => '^[A-Z][0-9](W|[0-9]) [A-Z]([0-9][A-Z][0-9]|[A-Z][0-9]{2}|[0-9][A-Z]{2})$', // Ireland
        'IL' => '^([0-9]{5}|[0-9]{7})$', // Israel
        'IM' => '^IM[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Isle of Man
        'IN' => '^[0-9]{3} ?[0-9]{3}$', // India  //
        'IO' => '^BBND 1ZZ$', // British Indian Ocean Territory
        'IQ' => self::FIVE_DIGIT_FORMAT, // Iraq
        'IR' => '^[0-9]{10}$', // Iran (Islamic Republic of)
        'IS' => self::THREE_DIGIT_FORMAT, // Iceland
        'IT' => self::FIVE_DIGIT_FORMAT, // Italy
        'JE' => '^JE[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Jersey
        'JM' => '^[0-9]{2}$', // Jamaica
        'JO' => self::FIVE_DIGIT_FORMAT, // Jordan
        'JP' => '^[0-9]{3}-[0-9]{4}$', // Japan
        'KE' => self::FIVE_DIGIT_FORMAT, // Kenya
        'KG' => self::SIX_DIGIT_FORMAT, // Kyrgyzstan
        'KH' => self::FIVE_DIGIT_FORMAT, // Cambodia
        'KI' => null, // Kiribati
        'KM' => null, // Comoros
        'KN' => null, // Saint Kitts and Nevis
        'KP' => null, // Korea (Democratic People's Republic of)
        'KR' => '^([0-9]{5}|[0-9]{3}-[0-9]{3})$', // Korea (Republic of)
        'KW' => self::FIVE_DIGIT_FORMAT, // Kuwait
        'KY' => '^KY[0-9]{1}-[0-9]{4}$', // Cayman Islands
        'KZ' => self::SIX_DIGIT_FORMAT, // Kazakhstan
        'LA' => self::FIVE_DIGIT_FORMAT, // Lao People's Democratic Republic
        'LB' => '^([0-9]{5}|[0-9]{4} [0-9]{4})$', // Lebanon
        'LC' => '^LC[0-9]{2}  [0-9]{3}$', // Saint Lucia
        'LI' => self::FOUR_DIGIT_FORMAT, // Liechtenstein
        'LK' => self::FIVE_DIGIT_FORMAT, // Sri Lanka
        'LR' => self::FOUR_DIGIT_FORMAT, // Liberia
        'LS' => self::THREE_DIGIT_FORMAT, // Lesotho
        'LT' => '^LT-[0-9]{5}$', // Lithuania
        'LU' => '^(L-)?[0-9]{4}$', // Luxembourg
        'LV' => '^LV-[0-9]{4}$', // Latvia
        'LY' => null, // Libya
        'MA' => self::FIVE_DIGIT_FORMAT, // Morocco
        'MC' => '^980[0-9]{2}$', // Monaco
        'MD' => '^MD-?[0-9]{4}$', // Moldova (Republic of)
        'ME' => self::FIVE_DIGIT_FORMAT, // Montenegro
        'MF' => '^97150$', // Saint Martin (French part)
        'MG' => self::THREE_DIGIT_FORMAT, // Madagascar
        'MH' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Marshall Islands
        'MK' => self::FOUR_DIGIT_FORMAT, // Macedonia (the former Yugoslav Republic of)
        'ML' => null, // Mali
        'MM' => self::FIVE_DIGIT_FORMAT, // Myanmar
        'MN' => self::SIX_DIGIT_FORMAT, // Mongolia
        'MO' => null, // Macao
        'MP' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Northern Mariana Islands
        'MQ' => '^972[0-9]{2}$', // Martinique
        'MR' => null, // Mauritania
        'MS' => '^MSR 1[0-9]{3}$', // Montserrat
        'MT' => '^[A-Z]{3} [0-9]{4}$', // Malta
        'MU' => self::FIVE_DIGIT_FORMAT, // Mauritius
        'MV' => self::FIVE_DIGIT_FORMAT, // Maldives
        'MW' => null, // Malawi
        'MX' => self::FIVE_DIGIT_FORMAT, // Mexico
        'MY' => self::FIVE_DIGIT_FORMAT, // Malaysia
        'MZ' => self::FOUR_DIGIT_FORMAT, // Mozambique
        'NA' => self::FIVE_DIGIT_FORMAT, // Namibia
        'NC' => '^988[0-9]{2}$', // New Caledonia
        'NE' => self::FOUR_DIGIT_FORMAT, // Niger
        'NF' => self::FOUR_DIGIT_FORMAT, // Norfolk Island
        'NG' => self::SIX_DIGIT_FORMAT, // Nigeria
        'NI' => self::FIVE_DIGIT_FORMAT, // Nicaragua
        'NL' => '^[0-9]{4} ?[A-Z]{2}$', // Netherlands
        'NO' => self::FOUR_DIGIT_FORMAT, // Norway
        'NP' => self::FIVE_DIGIT_FORMAT, // Nepal
        'NR' => null, // Nauru
        'NU' => null, // Niue
        'NZ' => self::FOUR_DIGIT_FORMAT, // New Zealand
        'PA' => self::FOUR_DIGIT_FORMAT, // Panama
        'PE' => '^([0-9]{5}|PE [0-9]{4})$', // Peru
        'PF' => '^987[0-9]{2}$', // French Polynesia
        'PG' => self::THREE_DIGIT_FORMAT, // Papua New Guinea
        'PH' => self::FOUR_DIGIT_FORMAT, // Philippines
        'PK' => self::FIVE_DIGIT_FORMAT, // Pakistan
        'PL' => '^[0-9]{2}-[0-9]{3}$', // Poland
        'PM' => '^97500$', // Saint Pierre and Miquelon
        'PN' => '^PCRN 1ZZ$', // Pitcairn
        'PR' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Puerto Rico
        'PS' => self::THREE_DIGIT_FORMAT, // Palestine, State of
        'PT' => '^[0-9]{4}-[0-9]{3}$', // Portugal
        'PW' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Palau
        'PY' => self::FOUR_DIGIT_FORMAT, // Paraguay
        'OM' => self::THREE_DIGIT_FORMAT, // Oman
        'QA' => null, // Qatar
        'RE' => '^974[0-9]{2}$', // La Réunion
        'RO' => self::SIX_DIGIT_FORMAT, // Romania
        'RS' => self::FIVE_DIGIT_FORMAT, // Serbia
        'RU' => self::SIX_DIGIT_FORMAT, // Russian Federation
        'RW' => null, // Rwanda
        'SA' => self::FIVE_DIGIT_FORMAT, // Saudi Arabia
        'SB' => null, // Solomon Islands
        'SC' => null, // Seychelles
        'SD' => self::FIVE_DIGIT_FORMAT, // Sudan
        'SE' => '^[0-9]{3} [0-9]{2}$', // Sweden
        'SG' => self::SIX_DIGIT_FORMAT, // Singapore
        'SH' => '^TDCU 1ZZ$', // Saint Helena, Ascension and Tristan da Cunha
        'SI' => '^([0-9]{4}|SI-[0-9]{4})$', // Slovenia
        'SJ' => self::FOUR_DIGIT_FORMAT, // Svalbard and Jan Mayen
        'SK' => '^[0-9]{3} [0-9]{2}$', // Slovakia
        'SL' => null, // Sierra Leone
        'SM' => '^4789[0-9]{1}$', // San Marino
        'SN' => self::FIVE_DIGIT_FORMAT, // Senegal
        'SO' => '^[A-Z]{2} [0-9]{5}$', // Somalia
        'SR' => null, // Suriname
        'SS' => self::FIVE_DIGIT_FORMAT, // South Sudan
        'ST' => null, // Sao Tome and Principe
        'SV' => self::FOUR_DIGIT_FORMAT, // El Salvador
        'SX' => null, // Sint Maarten (Dutch part)
        'SY' => null, // Syrian Arab Republic
        'SZ' => '^[A-Z]{1}[0-9]{3}$', // Swaziland
        'TC' => '^TKCA 1ZZ$', // Turks and Caicos Islands
        'TD' => null, // Chad
        'TF' => null, // French Southern Territories
        'TG' => null, // Togo
        'TH' => self::FIVE_DIGIT_FORMAT, // Thailand
        'TJ' => self::SIX_DIGIT_FORMAT, // Tajikistan
        'TK' => null, // Tokelau
        'TL' => null, // Timor-Leste
        'TM' => self::SIX_DIGIT_FORMAT, // Turkmenistan
        'TN' => self::FOUR_DIGIT_FORMAT, // Tunisia
        'TO' => null, // Tonga
        'TR' => self::FIVE_DIGIT_FORMAT, // Turkey
        'TT' => self::SIX_DIGIT_FORMAT, // Trinidad and Tobago
        'TV' => null, // Tuvalu
        'TW' => '^([0-9]{3}|[0-9]{3}-[0-9]{2})$', // Taiwan, Province of China
        'TZ' => self::FIVE_DIGIT_FORMAT, // Tanzania, United Republic of
        'UA' => self::FIVE_DIGIT_FORMAT, // Ukraine
        'UM' => self::FIVE_DIGIT_FORMAT, // United States Minor Outlying Islands
        'UG' => null, // Uganda
        'US' => '[0-9]{5}(\-[0-9]{4})?$', // United States of America
        'UY' => self::FIVE_DIGIT_FORMAT, // Uruguay
        'UZ' => self::SIX_DIGIT_FORMAT, // Uzbekistan
        'VA' => '^00120$', // Holy See
        'VC' => '^VC[0-9]{4}$', // Saint Vincent and the Grenadines
        'VE' => '^[0-9]{4}|[0-9]{4}-[A-Z]{1}$', // Venezuela (Bolivarian Republic of)
        'VG' => '^VG[0-9]{4}$', // Virgin Islands (British)
        'VI' => self::FIVE_FIVE_FOUR_DIGIT_FORMAT, // Virgin Islands (U.S.)
        'VN' => self::SIX_DIGIT_FORMAT, // Vietnam
        'VU' => null, // Vanuatu
        'WF' => '^986[0-9]{2}$', // Wallis and Futuna
        'WS' => '^WS[0-9]{4}$', // Samoa
        'YE' => null, // Yemen
        'YT' => '^976[0-9]{2}$', // Mayotte
        'ZA' => self::FOUR_DIGIT_FORMAT, // South Africa
        'ZM' => self::FIVE_DIGIT_FORMAT, // Zambia
        'ZW' => null, // Zimbabwe
    ];

    private ?PropertyAccessor $propertyAccessor = null;

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Postal) {
            throw new UnexpectedTypeException($constraint, Postal::class);
        }

        $path = $constraint->countryPropertyPath;

        if (null === $object = $this->context->getObject()) {
            throw new ConstraintDefinitionException('Missing object from context');
        }

        try {
            $country = $this->getPropertyAccessor()->getValue($object, $path);
        } catch (NoSuchPropertyException $e) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'Invalid property path "%s" provided to "%s" constraint: %s',
                    $path,
                    Postal::class,
                    $e->getMessage()
                ),
                0,
                $e
            );
        }

        if (!array_key_exists($country, self::POSTALS)) {
            $this->context->buildViolation($constraint->unknownCountryMessage)
                ->setParameter('{{ country }}', $this->formatValue($country))
                ->setCode(Postal::UNKNOWN_COUNTRY_ERROR)
                ->addViolation();

            return;
        }

        if (null === $value || '' === $value) {
            // There is no postal value
            if (null !== self::POSTALS[$country]) {
                // But the country requires one
                $this->context->buildViolation($constraint->missingPostalCodeMessage)
                    ->setCode(Postal::MISSING_ERROR)
                    ->addViolation();
            }
            return;
        }
        // There is a postal value
        if (null === self::POSTALS[$country]) {
            // But the country does not require one
            $this->context->buildViolation($constraint->noPostalCodeMessage)
                ->setCode(Postal::NO_POSTAL_CODE_SYSTEM)
                ->setParameter('{{ country }}', $this->formatValue($country))
                ->addViolation();

            return;
        }

        if (1 !== preg_match('/' . self::POSTALS[$country] . '/', $value)) {
            // And the country requires one but the postal format does not match the country's requirement
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Postal::INVALID_FORMAT_ERROR)
                ->addViolation();
        }
    }

    private function getPropertyAccessor(): PropertyAccessor
    {
        if (null === $this->propertyAccessor) {
            if (!class_exists(PropertyAccess::class)) {
                throw new \LogicException(
                    'Unable to use property path as the Symfony PropertyAccess component is not installed.'
                );
            }
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
