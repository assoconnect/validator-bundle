<?php

namespace AssoConnect\ValidatorBundle\Test;

use AssoConnect\ValidatorBundle\Tests\Functional\App\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

Abstract class ConstraintValidatorWithKernelTestCase extends KernelTestCase
{

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function setUp()
    {
        self::bootKernel();

        $this->validator = self::$kernel->getContainer()->get('validator');
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/AssoConnectValidatorBundle/');
    }

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    public abstract function getContraint($options = []) :Constraint;

    /**
     * @dataProvider providerValidValue
     */
    public function testValidValue($value, $options = []) :void
    {
        $errors = $this->validator->validate($value, $this->getContraint($options));

        $this->assertCount(0, $errors);
    }

    public abstract function providerValidValue() :array ;


    /**
     * @dataProvider providerInvalidValue
     */
    public function testInvalidValue($value, $options, array $expectedCodes) :void
    {
        $errors = $this->validator->validate($value, $this->getContraint($options));

        $actualCodes = [];
        $message = [];
        foreach($errors as $error){
            $actualCodes[] = $error->getCode();
            $message[] = $error->getCode() . ' (' . $error->getMessage() . ')';
        }

        sort($expectedCodes);
        sort($actualCodes);
        $this->assertSame($expectedCodes, $actualCodes, implode(', ', $message));
    }
    public abstract function providerInvalidValue() :array;

}