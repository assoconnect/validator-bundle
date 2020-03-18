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

The [EntityValidator](/src/Validator/Constraints/EntityValidator) sets up default validators
for your entity properties based on their Doctrine types.
For example,
````
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use AssoConnect\DoctrineValidatorBundle\Validator\Constraint as AssoConnectAssert;

/**
 * @AssoConnectAssert\Entity()
 */
Class BankAccount
{
	/**
	 * @ORM\Column(type="iban")
	 */
	public $iban;
}
````

is equivalent to this code:

````
<?php
namespace App\Entity;

use Symfony\Compotent\Validator\Constraint as Assert;
use Doctrine\ORM\Mapping as ORM;

Class BankAccount
{
	/**
	 * @Assert\Iban()
	 * @Assert\Length(27)
	 * @Assert\NotNull()
	 * @ORM\Column(type="string", length="27")
	 */
	public $iban;
}
````

## Custom constraints

You can use different constraints for a given type.
For instance, if you want to use [Symfony default email constraint](http://symfony.com/doc/current/reference/constraints/Email.html), you have to create your own constraint and validator:

````
<?php
namespace App\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;

Class CustomEntity extends Entity
{}
 
````

````
<?php
namespace App\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\EntityValidator;
use Symfony\Component\Validator\Constraints\Email;

Class CustomEntityValidator extends EntityValidator
{

    protected function getConstraintsForType(string $type) :array
    {
        if($type === 'email'){
            return [new Email()];
        }
        else{
            return parent::getConstraintsForType($type);
        }
    }

}
````