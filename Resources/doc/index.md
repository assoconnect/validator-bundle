# AssoConnectDoctrineValidatorBundle

## Installation with Symfony Flex

**a)** First download the bundle

`composer require assoconnect/validator-bundle`

**b)** Accept the contrib recipes installation from Symfony Flex
````
-  WARNING  assoconnect/validator-bundle (0.1): From gitlab.com/assoconnect/validator-bundle
    The recipe for this package comes from the "contrib" repository, which is open to community contributions.
    Do you want to execute this recipe?
    [y] Yes
    [n] No
    [a] Yes for all packages, only for the current installation session
    [p] Yes permanently, never ask again for this project
    (defaults to n): 
````

## Usage
You can use them as any other [Symfony validation](https://symfony.com/doc/current/validation.html):

````
<?php
namespace App\Entity;


use AssoConnect\ValidatorBundle\Validator\Constraint as AssoConnectAssert

Class User
{
	/**
	 * @AssoConnectAssert\Email()
	 */
	public $email;
}
````