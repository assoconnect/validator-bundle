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
 *
 */
class PostalValidator extends ConstraintValidator
{

    /**
     * @link https://en.wikipedia.org/wiki/List_of_postal_codes
     */
    private const POSTALS = [
        'AD' => '^AD[0-9]{3}$', // Andora
        'AE' => null, // United Arab Emirates
        'AF' => '^[0-9]{4}$', // Afghanistan
        'AG' => null, // Antigua and Barbuda
        'AL' => '^[0-9]{4}$', // Albania
        'AI' => '^AI-2640$', // Anguilla
        'AM' => '^[0-9]{4}$', // Armenia
        'AO' => null, // Angola
        'AQ' => '^BIQQ 1ZZ$', // Antarctica
        'AR' => '^[0-9]{4}$', // Argentina
        'AS' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // American Samoa
        'AT' => '^[0-9]{4}$', // Austria
        'AU' => '^[0-9]{4}$', // Australia
        'AW' => null, // Aruba
        'AX' => '^[0-9]{5}$', // Åland (Finlande territory)
        'AZ' => '^AZ[0-9]{4}$', // Azerbaijan
        'BA' => '^[0-9]{5}$', // Bosnia and Herzegovina
        'BB' => '^BB[0-9]{5}$', // Barbados
        'BD' => '^[0-9]{4}$', // Bangladesh
        'BE' => '^(B-)?[0-9]{4}$', // Belgium
        'BF' => null, // Burkina Faso
        'BG' => '^[0-9]{4}$', // Bulgaria
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
        'BT' => '^[0-9]{5}$', // Bhutan
        'BV' => null, // Bouvet Island
        'BW' => null, // Botswana
        'BY' => '^[0-9]{6}$', // Belarus
        'BZ' => null, // Belize
        'CA' => '^[A-Z][0-9][A-Z]( [0-9][A-Z][0-9])?$', // Canada
        'CC' => '^[0-9]{4}$', // Cocos (Keeling) Islands
        'CD' => null, // Congo (Democratic Republic of the)
        'CF' => null, // Central African Republic
        'CG' => null, // Congo
        'CH' => '^[0-9]{4}$', // Switzerland
        'CI' => null, // Côte d'Ivoire
        'CK' => null, // Cook Islands
        'CL' => '^([0-9]{7}|[0-9]{3}-[0-9]{4})$', // Chile
        'CM' => null, // Cameroon
        'CN' => '^[0-9]{6}$', // China
        'CO' => '^[0-9]{6}$', // Colombia
        'CR' => '^[0-9]{5}$', // Costa Rica
        'CU' => '^[0-9]{5}$', // Cuba
        'CV' => '^[0-9]{4}$', // Cabo Verde
        'CW' => null, // Curaçao
        'CX' => '^[0-9]{4}$', // Christmas Island
        'CY' => '^[0-9]{4}$', // Cyprus
        'CZ' => '^[0-9]{3} [0-9]{2}$', // Czech Republic
        'DE' => '^[0-9]{5}$', // Germany
        'DJ' => null, // Djibouti
        'DK' => '^[0-9]{4}$', // Denmark
        'DM' => null, // Dominica
        'DO' => '^[0-9]{5}$', // Dominican Republic
        'DZ' => '^[0-9]{5}$', // Algeria
        'EC' => '^[0-9]{6}$', // Ecuador
        'EE' => '^[0-9]{5}$', // Estonia
        'EG' => '^[0-9]{5}$', // Egypt
        'EH' => null, // Western Sahara
        'ER' => null, // Eritrea
        'ES' => '^[0-9]{5}$', // Spain
        'ET' => '^[0-9]{4}$', // Ethiopia
        'FI' => '^[0-9]{5}$', // Finland
        'FJ' => null, // Fiji
        'FK' => '^FIQQ 1ZZ$', // Falkland Islands (Malvinas)
        'FM' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Micronesia (Federated States of)
        'FO' => '^[0-9]{3}$', // Faroe Islands
        'FR' => '^[0-9]{5}$', // France
        'GA' => null, // Gabon
        'GB' => '^[A-Z]([A-Z][0-9][A-Z]|[0-9][A-Z]|[0-9]{1,2}|[A-Z][0-9]{1,2})( [0-9]{1}[A-Z]{2})?$',
        // United Kingdom of Great Britain and Northern Ireland
        'GD' => null, // Grenada
        'GE' => '^[0-9]{4}$', // Georgia
        'GF' => '^973[0-9]{2}$', // French Guiana
        'GG' => '^GY[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Guernsey
        'GH' => null, // Ghana
        'GI' => '^GX11 1AA$', // Gibraltar
        'GL' => '^[0-9]{4}$', // Greenland
        'GM' => null, // Gambia
        'GN' => '^[0-9]{3}$', // Guinea
        'GP' => '^971[0-9]{2}$', // Guadeloupe
        'GQ' => null, // Equatorial Guinea
        'GR' => '^[0-9]{3} [0-9]{2}$', // Greece
        'GS' => '^SIQQ 1ZZ$', // South Georgia and the South Sandwich Islands
        'GT' => '^[0-9]{5}$', // Guatemala
        'GU' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Guam
        'GW' => '^[0-9]{4}$', // Guinea-Bissau
        'GY' => null, // Guyana
        'HK' => null, // Hong Kong
        'HM' => null, // Heard Island and McDonald Islands
        'HN' => '^HN[0-9]{5}$', // Honduras
        'HR' => '^[0-9]{5}$', // Croatia
        'HT' => '^[0-9]{4}$', // Haiti
        'HU' => '^[0-9]{4}$', // Hungary
        'ID' => '^[0-9]{5}$', // Indonesia
        'IE' => '^[A-Z][0-9](W|[0-9]) [A-Z]([0-9][A-Z][0-9]|[A-Z][0-9]{2}|[0-9][A-Z]{2})$', // Ireland
        'IL' => '^([0-9]{5}|[0-9]{7})$', // Israel
        'IM' => '^IM[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Isle of Man
        'IN' => '^[0-9]{3} ?[0-9]{3}$', // India  //
        'IO' => '^BBND 1ZZ$', // British Indian Ocean Territory
        'IQ' => '^[0-9]{5}$', // Iraq
        'IR' => '^[0-9]{10}$', // Iran (Islamic Republic of)
        'IS' => '^[0-9]{3}$', // Iceland
        'IT' => '^[0-9]{5}$', // Italy
        'JE' => '^JE[0-9]{1,2} [0-9]{1}[A-Z]{2}$', // Jersey
        'JM' => '^[0-9]{2}$', // Jamaica
        'JO' => '^[0-9]{5}$', // Jordan
        'JP' => '^[0-9]{3}-[0-9]{4}$', // Japan
        'KE' => '^[0-9]{5}$', // Kenya
        'KG' => '^[0-9]{6}$', // Kyrgyzstan
        'KH' => '^[0-9]{5}$', // Cambodia
        'KI' => null, // Kiribati
        'KM' => null, // Comoros
        'KN' => null, // Saint Kitts and Nevis
        'KP' => null, // Korea (Democratic People's Republic of)
        'KR' => '^([0-9]{5}|[0-9]{3}-[0-9]{3})$', // Korea (Republic of)
        'KW' => '^[0-9]{5}$', // Kuwait
        'KY' => '^KY[0-9]{1}-[0-9]{4}$', // Cayman Islands
        'KZ' => '^[0-9]{6}$', // Kazakhstan
        'LA' => '^[0-9]{5}$', // Lao People's Democratic Republic
        'LB' => '^([0-9]{5}|[0-9]{4} [0-9]{4})$', // Lebanon
        'LC' => '^LC[0-9]{2}  [0-9]{3}$', // Saint Lucia
        'LI' => '^[0-9]{4}$', // Liechtenstein
        'LK' => '^[0-9]{5}$', // Sri Lanka
        'LR' => '^[0-9]{4}$', // Liberia
        'LS' => '^[0-9]{3}$', // Lesotho
        'LT' => '^LT-[0-9]{5}$', // Lithuania
        'LU' => '^(L-)?[0-9]{4}$', // Luxembourg
        'LV' => '^LV-[0-9]{4}$', // Latvia
        'LY' => null, // Libya
        'MA' => '^[0-9]{5}$', // Morocco
        'MC' => '^980[0-9]{2}$', // Monaco
        'MD' => '^MD-?[0-9]{4}$', // Moldova (Republic of)
        'ME' => '^[0-9]{5}$', // Montenegro
        'MF' => '^97150$', // Saint Martin (French part)
        'MG' => '^[0-9]{3}$', // Madagascar
        'MH' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Marshall Islands
        'MK' => '^[0-9]{4}$', // Macedonia (the former Yugoslav Republic of)
        'ML' => null, // Mali
        'MM' => '^[0-9]{5}$', // Myanmar
        'MN' => '^[0-9]{6}$', // Mongolia
        'MO' => null, // Macao
        'MP' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Northern Mariana Islands
        'MQ' => '^972[0-9]{2}$', // Martinique
        'MR' => null, // Mauritania
        'MS' => '^MSR 1[0-9]{3}$', // Montserrat
        'MT' => '^[A-Z]{3} [0-9]{4}$', // Malta
        'MU' => '^[0-9]{5}$', // Mauritius
        'MV' => '^[0-9]{5}$', // Maldives
        'MW' => null, // Malawi
        'MX' => '^[0-9]{5}$', // Mexico
        'MY' => '^[0-9]{5}$', // Malaysia
        'MZ' => '^[0-9]{4}$', // Mozambique
        'NA' => '^[0-9]{5}$', // Namibia
        'NC' => '^988[0-9]{2}$', // New Caledonia
        'NE' => '^[0-9]{4}$', // Niger
        'NF' => '^[0-9]{4}$', // Norfolk Island
        'NG' => '^[0-9]{6}$', // Nigeria
        'NI' => '^[0-9]{5}$', // Nicaragua
        'NL' => '^[0-9]{4} ?[A-Z]{2}$', // Netherlands
        'NO' => '^[0-9]{4}$', // Norway
        'NP' => '^[0-9]{5}$', // Nepal
        'NR' => null, // Nauru
        'NU' => null, // Niue
        'NZ' => '^[0-9]{4}$', // New Zealand
        'PA' => '^[0-9]{4}$', // Panama
        'PE' => '^([0-9]{5}|PE [0-9]{4})$', // Peru
        'PF' => '^987[0-9]{2}$', // French Polynesia
        'PG' => '^[0-9]{3}$', // Papua New Guinea
        'PH' => '^[0-9]{4}$', // Philippines
        'PK' => '^[0-9]{5}$', // Pakistan
        'PL' => '^[0-9]{2}-[0-9]{3}$', // Poland
        'PM' => '^97500$', // Saint Pierre and Miquelon
        'PN' => '^PCRN 1ZZ$', // Pitcairn
        'PR' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Puerto Rico
        'PS' => '^[0-9]{3}$', // Palestine, State of
        'PT' => '^[0-9]{4}-[0-9]{3}$', // Portugal
        'PW' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Palau
        'PY' => '^[0-9]{4}$', // Paraguay
        'OM' => '^[0-9]{3}$', // Oman
        'QA' => null, // Qatar
        'RE' => '^974[0-9]{2}$', // La Réunion
        'RO' => '^[0-9]{6}$', // Romania
        'RS' => '^[0-9]{5}$', // Serbia
        'RU' => '^[0-9]{6}$', // Russian Federation
        'RW' => null, // Rwanda
        'SA' => '^[0-9]{5}$', // Saudi Arabia
        'SB' => null, // Solomon Islands
        'SC' => null, // Seychelles
        'SD' => '^[0-9]{5}$', // Sudan
        'SE' => '^[0-9]{3} [0-9]{2}$', // Sweden
        'SG' => '^[0-9]{6}$', // Singapore
        'SH' => '^TDCU 1ZZ$', // Saint Helena, Ascension and Tristan da Cunha
        'SI' => '^([0-9]{4}|SI-[0-9]{4})$', // Slovenia
        'SJ' => '^[0-9]{4}$', // Svalbard and Jan Mayen
        'SK' => '^[0-9]{3} [0-9]{2}$', // Slovakia
        'SL' => null, // Sierra Leone
        'SM' => '^4789[0-9]{1}$', // San Marino
        'SN' => '^[0-9]{5}$', // Senegal
        'SO' => '^[A-Z]{2} [0-9]{5}$', // Somalia
        'SR' => null, // Suriname
        'SS' => '^[0-9]{5}$', // South Sudan
        'ST' => null, // Sao Tome and Principe
        'SV' => '^[0-9]{4}$', // El Salvador
        'SX' => null, // Sint Maarten (Dutch part)
        'SY' => null, // Syrian Arab Republic
        'SZ' => '^[A-Z]{1}[0-9]{3}$', // Swaziland
        'TC' => '^TKCA 1ZZ$', // Turks and Caicos Islands
        'TD' => null, // Chad
        'TF' => null, // French Southern Territories
        'TG' => null, // Togo
        'TH' => '^[0-9]{5}$', // Thailand
        'TJ' => '^[0-9]{6}$', // Tajikistan
        'TK' => null, // Tokelau
        'TL' => null, // Timor-Leste
        'TM' => '^[0-9]{6}$', // Turkmenistan
        'TN' => '^[0-9]{4}$', // Tunisia
        'TO' => null, // Tonga
        'TR' => '^[0-9]{5}$', // Turkey
        'TT' => '^[0-9]{6}$', // Trinidad and Tobago
        'TV' => null, // Tuvalu
        'TW' => '^([0-9]{3}|[0-9]{3}-[0-9]{2})$', // Taiwan, Province of China
        'TZ' => '^[0-9]{5}$', // Tanzania, United Republic of
        'UA' => '^[0-9]{5}$', // Ukraine
        'UM' => '^[0-9]{5}$', // United States Minor Outlying Islands
        'UG' => null, // Uganda
        'US' => '^[0-9]{5}$', // United States of America
        'UY' => '^[0-9]{5}$', // Uruguay
        'UZ' => '^[0-9]{6}$', // Uzbekistan
        'VA' => '^00120$', // Holy See
        'VC' => '^VC[0-9]{4}$', // Saint Vincent and the Grenadines
        'VE' => '^[0-9]{4}|[0-9]{4}-[A-Z]{1}$', // Venezuela (Bolivarian Republic of)
        'VG' => '^VG[0-9]{4}$', // Virgin Islands (British)
        'VI' => '^([0-9]{5}|[0-9]{5}-[0-9]{4})$', // Virgin Islands (U.S.)
        'VN' => '^[0-9]{6}$', // Vietnam
        'VU' => null, // Vanuatu
        'WF' => '^986[0-9]{2}$', // Wallis and Futuna
        'WS' => '^WS[0-9]{4}$', // Samoa
        'YE' => null, // Yemen
        'YT' => '^976[0-9]{2}$', // Mayotte
        'ZA' => '^[0-9]{4}$', // South Africa
        'ZM' => '^[0-9]{5}$', // Zambia
        'ZW' => null, // Zimbabwe
    ];

    private $propertyAccessor;

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Postal) {
            throw new UnexpectedTypeException($constraint, Postal::class);
        }

        if (null === $path = $constraint->countryPropertyPath) {
            throw new ConstraintDefinitionException('Missing property path');
        }

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
            throw new \DomainException('Unexpected country');
        }

        if (null === $value || '' === $value) {
            // There is no postal value
            if (null !== self::POSTALS[$country]) {
                // But the country requires one
                $this->context->buildViolation($constraint::getErrorName(Postal::MISSING_ERROR))
                    ->setCode(Postal::MISSING_ERROR)
                    ->addViolation();
            }
            return;
        }
        // There is a postal value
        if (null === self::POSTALS[$country]) {
            // But the country does not require one
            $this->context->buildViolation($constraint::getErrorName(Postal::NOT_REQUIRED_ERROR))
                ->setCode(Postal::NOT_REQUIRED_ERROR)
                ->addViolation();

            return;
        }

        if (!preg_match('/' . self::POSTALS[$country] . '/', $value)) {
            // And the country requires one but the postal format does not match the country's requirement
            $this->context->buildViolation($constraint::getErrorName(Postal::INVALID_FORMAT_ERROR))
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