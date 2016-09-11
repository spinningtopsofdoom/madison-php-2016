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

class FriendRelationship {

    protected $people;
    protected $relationships;

    function __construct($people) {
        $this->people = $people;

        $this->relationships = [];
        foreach($people as $person) {
            $this->relationships[$person] = [];
        }
    }

    function addRelationship($person, $new_friend) {
        if ($new_friend !== $person) {
            $this->relationships[$person][$new_friend] = true;
            $this->relationships[$new_friend][$person] = true;
        }

        return $this;
    }

    function removeRelationship($person, $not_my_friend) {
        if (isset($this->relationships[$person][$not_my_friend])) {
            unset($this->relationships[$person][$not_my_friend]);
            unset($this->relationships[$not_my_friend][$person]);
        }

        return $this;
    }

    function getPeople() {
        return $this->people;
    }

    function getRelationships() {
        return $this->relationships;
    }
}

$people = ['Damaris', 'Liyam', 'Wye', 'Isbelle', 'Rebexa', 'Aebby'];

$relationship_map = Generator\associative([
    'people' => Generator\constant($people)
]);
$fresh_relationship = Generator\map(
    function($relationship_map) {
        return new FriendRelationship($relationship_map['people']);
    },
    $relationship_map
);

echo "Generate new FriendRelationship object\n";
var_dump(generate($fresh_relationship));

$operation = Generator\tuple(
    Generator\elements(['addRelationship', 'removeRelationship']),
    Generator\elements($people),
    Generator\elements($people)
);
$operations = Generator\seq($operation);

echo "Generate FriendRelationship operations\n";
var_dump(generate($operations));

function apply_operation($relationship, $operation) {
    $values = array_slice($operation, 1);
    $operation = $operation[0];

    switch($operation) {
    case 'addRelationship':
        list($person, $friend) = $values;
        return $relationship->addRelationship($person, $friend);
        break;
    case 'removeRelationship':
        list($person, $not_a_friend) = $values;
        return $relationship->removeRelationship($person, $not_a_friend);
        break;
    }

    return $relationship;
}

function apply_operations($relationship, $operations) {
    foreach($operations as $operation) {
        $relationship = apply_operation($relationship, $operation);
    }

    return $relationship;
}

$modified_relationship = Generator\map(
    function($relationship_and_operations) {
        list($relationship, $operations) = $relationship_and_operations;

        return apply_operations($relationship, $operations);
    },
    Generator\tuple(
        $fresh_relationship,
        $operations
    )
);

echo "Generate FriendRelationship with adding and removing friendships\n";
var_dump(generate($modified_relationship));
?>
