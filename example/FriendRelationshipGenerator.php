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

class Friendships {

    protected $friendships;

    function __construct() {
        $this->friendships = [];
    }

    function addFriendship($person, $new_friend) {
        if (! isset($this->friendships[$person])) {
            $this->friendships[$person] = [];
        }
        if ($new_friend !== $person) {
            $this->friendships[$person][$new_friend] = true;
            $this->friendships[$new_friend][$person] = true;
        }

        return $this;
    }

    function removeFriendship($person, $not_my_friend) {
        if ($this->friends($person, $not_my_friend)) {
            unset($this->friendships[$person][$not_my_friend]);
            unset($this->friendships[$not_my_friend][$person]);
        }

        return $this;
    }

    function getPeople() {
        return array_keys($this->friendships);
    }

    function friends($person, $friend) {
        return isset($this->friendships[$person]) && isset($this->friendships[$person][$friend]);
    }

    function getFriends($person) {
        if (! isset($this->friendships[$person])) {
            return [];
        }
        return array_keys($this->friendships[$person]);
    }
}
$people = ['Damaris', 'Liyam', 'Wye', 'Isbelle', 'Rebexa', 'Aebby'];

$fresh_friendship = Generator\map(
    function($friendship_map) {
        return new Friendships();
    },
    Generator\associative([])
);

echo "Generate new Friendships object\n";
echo "-------------------------------\n";
var_dump(generate($fresh_friendship));

$operation = Generator\tuple(
    Generator\elements(['addFriendship', 'removeFriendship']),
    Generator\elements($people),
    Generator\elements($people)
);
$operations = Generator\seq($operation);

echo "\n";
echo "Generate Friendships operations\n";
echo "-------------------------------\n";
var_dump(generate($operations));

function apply_operation($friendship, $operation) {
    $values = array_slice($operation, 1);
    $operation = $operation[0];

    switch($operation) {
    case 'addFriendship':
        list($person, $friend) = $values;
        return $friendship->addFriendship($person, $friend);
        break;
    case 'removeFriendship':
        list($person, $not_a_friend) = $values;
        return $friendship->removeFriendship($person, $not_a_friend);
        break;
    }

    return $friendship;
}

function apply_operations($friendship, $operations) {
    foreach($operations as $operation) {
        $friendship = apply_operation($friendship, $operation);
    }

    return $friendship;
}

$modified_friendship = Generator\map(
    function($friendship_and_operations) {
        list($friendship, $operations) = $friendship_and_operations;

        return apply_operations($friendship, $operations);
    },
    Generator\tuple(
        $fresh_friendship,
        $operations
    )
);

echo "\n";
echo "Generate modified Friendships with adding and removing friendships\n";
echo "------------------------------------------------------------------\n";
var_dump(generate($modified_friendship));
?>
