<?php
//Bring in composer autoloader
require 'vendor/autoload.php';
//Setup Eris generators
Eris\TestTrait::erisSetupBeforeClass();
//Load up Generator interface
use Eris\Generator;
/*
 * Generates samples based on a generator
 * @param Eris\Generator $generator: Generator that creates the sample
 * @param integer $times: Number of samples to create
 * @param integer $seed: Optinal seed for reproducing samples
 * */
function sample($generator, $times, $seed = 10) {
    mt_srand($seed);
    return Eris\Sample::of($generator, 'mt_rand')->repeat($times)->collected();
}
/*
 * Generates a single sample based on a generator
 * @param Eris\Generator $generator: Generator that creates the sample
 * @param integer $seed: Optinal seed for reproducing samples
 * */
function generate($generator, $seed = 10) {
    mt_srand($seed);
    return Eris\Sample::of($generator, 'mt_rand')->repeat(1)->collected()[0];
}

$scale = Generator\elements(['C', 'F']);
$degrees = Generator\choose(-100, 100);
$reading = Generator\associative(['scale' => $scale, 'degrees' => $degrees]);
$measurements = Generator\vector(10, $reading);

echo "Base Temperatures\n";
var_dump(generate($measurements));

$fahrenhiet = Generator\constant('F');
$celsius = Generator\constant('C');
$three_more_f_scale = Generator\frequency([3, $fahrenhiet], [1, $celsius]);
$three_more_f_reading = Generator\associative(['scale' => $three_more_f_scale, 'degrees' => $degrees]);
$three_more_f_measurements = Generator\vector(10, $three_more_f_reading);

echo "Three More F Temperatures\n";
var_dump(generate($three_more_f_measurements));

$accurate_degrees = Generator\map(
    function($d) {
      list($base, $precision) = $d;
      return round(($base + $precision), 2);},
    Generator\tuple(Generator\float(), $degrees));
$accurate_reading = Generator\associative(['scale' => $three_more_f_scale, 'degrees' => $accurate_degrees]);
$accurate_measurements = Generator\vector(10, $accurate_reading);

echo "Accurate Temperatures\n";
var_dump(generate($accurate_measurements));
?>
