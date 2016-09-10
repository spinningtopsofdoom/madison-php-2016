<?php
//Bring in Eris Generator interface
use Eris\Generator;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    //Setup test generators and Eris methods
    use Eris\TestTrait;

    public function testIsInteger()
    {
        $number_generator = Generator\int();
        /*
         * forAll takes a generator to create inputs
         * then takes a generated input and then runs the test
         */
        $this->forAll($number_generator)
             ->then(function ($number) {
                 $this->assertTrue(is_integer($number));
             }
        );
    }
}
?>
