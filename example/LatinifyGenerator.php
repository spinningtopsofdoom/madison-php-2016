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

$non_cat_word = Generator\elements(['where', 'is', 'the', 'fat', 'with', 'a', 'on']);
$cat_word = Generator\constant('cat');
$cat_within_word = Generator\elements(['hepcat', 'catamaran']);

$word_gen = Generator\frequency([10, $non_cat_word], [1, $cat_word], [1, $cat_within_word]);
$cat_sentence_gen = Generator\map(
    function($words) { return implode($words, ' '); },
    Generator\seq($word_gen));

var_dump(sample($cat_sentence_gen, 10));
?>
