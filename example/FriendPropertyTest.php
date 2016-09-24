<?php
//Bring in Eris Generator interface
use Eris\Generator;

class FriendRelationship {

    protected $people;
    protected $relationships;

    function __construct() {
        $this->relationships = [];
    }

    function addRelationship($person, $new_friend) {
        if (! isset($this->relationships[$person])) {
            $this->relationships[$person] = [];
        }
        if ($new_friend !== $person) {
            $this->relationships[$person][$new_friend] = true;
            $this->relationships[$new_friend][$person] = true;
        }

        return $this;
    }

    function removeRelationship($person, $not_my_friend) {
        if ($this->hasFriendship($person, $not_my_friend)) {
            unset($this->relationships[$person][$not_my_friend]);
            unset($this->relationships[$not_my_friend][$person]);
        }

        return $this;
    }

    function getPeople() {
        return array_keys($this->relationships);
    }

    function hasFriendship($person, $friend) {
        return isset($this->relationships[$person]) && isset($this->relationships[$person][$friend]);
    }

    function getFriends($person) {
        if (! isset($this->relationships[$person])) {
            return [];
        }
        return array_keys($this->relationships[$person]);
    }
}


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

class FriendTest extends \PHPUnit_Framework_TestCase
{
    //Setup test generators and Eris methods
    use Eris\TestTrait;

    protected $relationship_gen;

    public function setUp() {
        $people = ['Damaris', 'Liyam', 'Wye', 'Isbelle', 'Rebexa', 'Aebby'];

        $fresh_relationship = Generator\map(
            function($relationship_map) {
                return new FriendRelationship();
            },
            Generator\associative([])
        );

        $operation = Generator\tuple(
            Generator\elements(['addRelationship', 'removeRelationship']),
            Generator\elements($people),
            Generator\elements($people));
        $operations = Generator\seq($operation);

        $this->relationship_gen = Generator\bind(
            $operations,
            function($operations) use ($fresh_relationship){
                return Generator\map(
                    function($relationship) use ($operations) {
                        $new_relationship = apply_operations($relationship, $operations);
                        return [$new_relationship, $operations];
                    },
                    $fresh_relationship);
            });
    }

    public function testNoSelfFriend()
    {
        $this->forAll($this->relationship_gen)
             ->then(function ($relationship_and_operations) {
                list($relationship, $operations) = $relationship_and_operations;
                $operation_string = var_export($operations, true);

                $self_friended = false;
                $self_friend = "";
                $people = $relationship->getPeople();
                foreach($people as $person) {
                  $self_friended = $self_friended || $relationship->hasFriendship($person, $person);
                  if ($self_friended) {
                    $self_friend = $person;
                    break;
                  }
                }
                $this->assertFalse($self_friended, "Operations ({$operation_string}) lead to ($self_friend) self friending");
             }
        );
    }

    public function testSymetricalFriendship()
    {
        $this->forAll($this->relationship_gen)
             ->then(function ($relationship_and_operations) {
                list($relationship, $operations) = $relationship_and_operations;
                $operation_string = var_export($operations, true);
                $people = $relationship->getPeople();

                $symetrical = true;
                $bad_person = "";
                $bad_person_friends = '';
                $bad_friend = "";
                $bad_friend_friends = '';
                $people = $relationship->getPeople();
                foreach($people as $person) {
                    $friends = $relationship->getFriends($person);
                    foreach($friends as $friend) {
                        $symetrical = $symetrical && $relationship->hasFriendship($friend, $person);
                        if (! $symetrical) {
                            $bad_person = $person;
                            $bad_person_friends = "'". implode("', '", $friends) . "'";
                            $bad_friend = $friend;
                            $bad_friend_friends = "'". implode("', '", $relationship->getFriends($friend)) . "'";
                            break;
                        }
                    }
                }
                $this->assertTrue($symetrical, "Operations ({$operation_string}) lead to asymetrical friendship between ({$bad_person} with friends {$bad_person_friends}) and ({$bad_friend} with friends {$bad_friend_friends})");
             }
        );
    }
}
?>
