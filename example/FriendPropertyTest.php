<?php
//Bring in Eris Generator interface
use Eris\Generator;

class Friendships {

    protected $people;
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

class FriendTest extends \PHPUnit_Framework_TestCase
{
    //Setup test generators and Eris methods
    use Eris\TestTrait;

    protected $friendship_gen;

    public function setUp() {
        $people = ['Damaris', 'Liyam', 'Wye', 'Isbelle', 'Rebexa', 'Aebby'];

        $fresh_friendship = Generator\map(
            function($friendship_map) {
                return new Friendships();
            },
            Generator\associative([])
        );

        $operation = Generator\tuple(
            Generator\elements(['addFriendship', 'removeFriendship']),
            Generator\elements($people),
            Generator\elements($people));
        $operations = Generator\seq($operation);

        $this->friendship_gen = Generator\bind(
            $operations,
            function($operations) use ($fresh_friendship){
                return Generator\map(
                    function($friendship) use ($operations) {
                        $new_friendship = apply_operations($friendship, $operations);
                        return [$new_friendship, $operations];
                    },
                    $fresh_friendship);
            });
    }

    public function testNoSelfFriend()
    {
        $this->forAll($this->friendship_gen)
             ->then(function ($friendship_and_operations) {
                list($friendship, $operations) = $friendship_and_operations;
                $operation_string = var_export($operations, true);

                $self_friended = false;
                $self_friend = "";
                $people = $friendship->getPeople();
                foreach($people as $person) {
                  $self_friended = $self_friended || $friendship->friends($person, $person);
                  if ($self_friended) {
                    $self_friend = $person;
                    break;
                  }
                }
                $this->assertFalse($self_friended, "Operations ({$operation_string}) lead to ($self_friend) self friending");
             }
        );
    }

    public function testSymmetricalFriendship()
    {
        $this->forAll($this->friendship_gen)
             ->then(function ($friendship_and_operations) {
                list($friendship, $operations) = $friendship_and_operations;
                $operation_string = var_export($operations, true);

                $symmetrical = true;
                $bad_person = "";
                $bad_person_friends = '';
                $bad_friend = "";
                $bad_friend_friends = '';
                $people = $friendship->getPeople();
                foreach($people as $person) {
                    $friends = $friendship->getFriends($person);
                    foreach($friends as $friend) {
                        $symmetrical = $symmetrical && $friendship->friends($friend, $person);
                        if (! $symmetrical) {
                            $bad_person = $person;
                            $bad_person_friends = "'". implode("', '", $friends) . "'";
                            $bad_friend = $friend;
                            $bad_friend_friends = "'". implode("', '", $friendship->getFriends($friend)) . "'";
                            break;
                        }
                    }
                }
                $this->assertTrue($symmetrical, "Operations ({$operation_string}) lead to asymmetrical friendship between ({$bad_person} with friends {$bad_person_friends}) and ({$bad_friend} with friends {$bad_friend_friends})");
             }
        );
    }
}
?>
