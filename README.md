# AssoConnectValidatorBundle

[![Build Status](https://github.com/assoconnect/validator-bundle/actions/workflows/build.yml/badge.svg)](https://github.com/assoconnect/validator-bundle/actions/workflows/build.yml)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=assoconnect_validator-bundle&metric=coverage)](https://sonarcloud.io/dashboard?id=assoconnect_validator-bundle)

This Symfony bundle provides validators for common scalar values:

- [Email address](/src/Validator/Constraints/EmailValidator.php)
- [Phone Number](/src/Validator/Constraints/PhoneValidator.php)
- [Latitude](/src/Validator/Constraints/LatitudeValidator.php) and [Longitude](/src/Validator/Constraints/LongitudeValidator.php) values
- [Monetary value](/src/Validator/Constraints/MoneyValidator.php)
- [Timezone value](/src/Validator/Constraints/TimezoneValidator.php)
- [French RNA value](/src/Validator/Constraints/FrenchRnaValidator.php)
- [French SIREN value](/src/Validator/Constraints/FrenchSirenValidator.php)
- [French SIRET value](/src/Validator/Constraints/FrenchSiretValidator.php)

See the different constraints source code for available options.

[How to use](doc/index.md)
