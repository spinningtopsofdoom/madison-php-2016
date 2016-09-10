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

$types = ['school', 'clothing', 'food'];
$type_to_item = [
        'school' => ['notebook', 'pencil'],
        'clothing' => ['shirt', 'hat'],
        'food' => ['chips', 'apple']];

$type = Generator\elements($types);
$type_and_item = Generator\bind($type,
    function($type) use ($type_to_item){
        return Generator\tuple(
            Generator\constant($type),
            Generator\elements($type_to_item[$type]));
    });
$items_picked_up = Generator\seq($type_and_item);

var_dump(generate($items_picked_up));
?>
