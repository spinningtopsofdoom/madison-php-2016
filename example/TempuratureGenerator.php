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

$scale = Generator\elements(['C', 'F', 'K']);
$degrees = Generator\choose(0, 100);
$reading = Generator\associative(['scale' => $scale, 'degrees' => $degrees]);
$measurements = Generator\vector(10, $reading);

echo "Base Temperatures\n";
var_dump(generate($measurements));

$fahrenhiet = Generator\constant('F');
$celsius = Generator\constant('C');
$kelvin = Generator\constant('K');
$dist_scale = Generator\frequency([6, $fahrenhiet], [3, $celsius], [1, $kelvin]);
$dist_reading = Generator\associative(['scale' => $dist_scale, 'degrees' => $degrees]);
$dist_measurements = Generator\vector(10, $dist_reading);

echo "Realistically Distibuted Scale\n";
var_dump(generate($dist_measurements));

$realistic_reading = Generator\bind(
    $dist_scale,
    function($scale) {
        $degrees = ['F' => Generator\choose(0, 100),
            'C' => Generator\choose(0, 32),
            'K' => Generator\choose(280, 310)];
        return Generator\associative(['scale' => $scale, 'degrees' => $degrees[$scale]]);
    }
);
$realistic_measurements = Generator\vector(10, $realistic_reading);
echo "Realistic Temperatures\n";
var_dump(generate($realistic_measurements));

$accurate_reading = Generator\map(
    function($gen_data) {
      list($precision, $reading) = $gen_data;
      $reading['degrees'] = round(($reading['degrees'] + $precision), 2);
      return $reading;},
    Generator\tuple(Generator\float(), $realistic_reading));
$accurate_measurements = Generator\vector(10, $accurate_reading);
echo "Accurate Temperatures\n";
var_dump(generate($accurate_measurements));
?>
