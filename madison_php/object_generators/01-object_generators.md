!SLIDE

#That's fine for functions but what about objects?

!SLIDE

#Objects and their methods can be tested
##The trick is to convert object method calls to data

!SLIDE

## Example object is friendship tracker that tracks friendships between a group of people

!SLIDE

##Friendship tracker that has methods
- `addFriendship('Bob', 'Alice')`
    - Adds a new friendship between 'Bob' and 'Alice'
- `removeFriendship('Bob', 'Alice')`
    - Removes the friendship between 'Bob' and 'Alice'
- `friends('Alice', 'Bob')`
    - Checks if 'Alice' and 'Bob' have a friendship
- `getFriends('Alice')`
    - Get all of 'Alice's friends
- `getPeople()`
    - Gets the people in our database ('Alice' and 'Bob')

!SLIDE

##Typical Usage

    @@@ php
    $friends = new Friendships();
    $friends->addFriendship('Alice', 'Bob');
    $friends->addFriendship('Alice', 'Carol');
    $friends->addFriendship('Dan', 'Bob');
    $friends->removeFriendship('Alice', 'Bob');

!SLIDE

##This can be represented in data like so

    @@@ php
    $operations = [['addFriendship', 'Alice', 'Bob'],
        ['addFriendship', 'Alice', 'Carol'],
        ['addFriendship', 'Dan', 'Bob'],
        ['removeFriendship', 'Alice', 'Bob']];

!SLIDE

##Similar to a todo list
###The actions and information on the list are not the actual actions

![Todo List](../../images/todo_list.png)

!SLIDE

##Create a new Friendship tracker

    @@@ php
    $fresh_friendship = Generator\map(
        function($friendship_map) {
            return new Friendships();
        },
        Generator\associative([])
    );

!SLIDE

##Friend friendship operation generator

    @@@ php
    $methods = Generator\elements([
      'addFriendship',
      'removeFriendship']);
    $operation = Generator\tuple(
        $methods,
        Generator\elements($people),
        Generator\elements($people)
    );
    $operations = Generator\seq($operation);

!SLIDE

##Create the Friend Tracker with operations that modified it's state

    @@@ php
    $modified_friendship = Generator\bind(
        $operations,
        function($operations) use ($fresh_friendship) {
          return Generator\map(
            function($friendship) use ($operations) {
              $new_friendship = apply_operations($friendship,
                                                   $operations);
              return [$new_friendship, $operations];
            },
            $fresh_friendship);
        });

!SLIDE

# No self friending

    @@@ php
    $self_friended = false;
    $people = $friendships->getPeople();
    foreach($people as $person) {
      if ($friendships->friends($person, $person)) {
          $self_friended = true;
      }
    }

    $self_friended === false;

!SLIDE

# Symmetrical Friendship

    @@@ php
    $symmetrical = true;
    $people = $friendship->getPeople();
    foreach($people as $person) {
        $friends = $friendship->getFriends($person);
        foreach($friends as $friend) {
          if ($friendships->friends($friend, $person)) {
            $symmetrical = false;
          }
        }
    }

    $symmetrical === true;
