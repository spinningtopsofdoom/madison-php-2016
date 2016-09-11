!SLIDE

#That's fine for functions but what about objects?

!SLIDE

#Objects and their methods can be tested
##The trick is to convert object method calls to data

!SLIDE

##Friendship tracker that has methods
- addRelationship('Bob', 'Alice');
    - Adds a new friendship between 'Bob' and 'Alice'
- removeRelationship('Bob', 'Alice');
    - Removes the friendship between 'Bob' and 'Alice'

!SLIDE

##Typical Usage

    @php
    $friends = new FriendRelationship();
    $friends->addRelationship('Alice', 'Bob');
    $friends->addRelationship('Alice', 'Carol');
    $friends->addRelationship('Dan', 'Bob');
    $friends->removeRelationship('Alice', 'Bob');

!SLIDE

##This can be represented in data like so

    @php
    $operationsi = [['addRelationship', 'Alice', 'Bob'],
        ['addRelationship', 'Alice', 'Carol'],
        ['addRelationship', 'Dan', 'Bob'],
        ['removeRelationship', 'Alice', 'Bob']];

!SLIDE

##Similar to a todo list
###The actions and information on the list are not the actual actions

![Todo List](../../images/todo_list.png)

!SLIDE

##Create a new Friendship tracker

    @php
    $relationship_map = Generator\associative([
        'people' => Generator\constant($people)
    ]);
    $fresh_relationship = Generator\map(
        function($relationship_map) {
            return new FriendRelationship($relationship_map['people']);
        },
        $relationship_map
    );

!SLIDE

##Friend Relationship operation generator

    @php
    $operation = Generator\tuple(
        Generator\elements(['addRelationship', 'removeRelationship']),
        Generator\elements($people),
        Generator\elements($people)
    );
    $operations = Generator\seq($operation);

!SLIDE

##Create the Friend Tracker with operations that modified it's state

    @php
    $modified_friendship = Generator\bind(
        $operations,
        function($operations) use ($fresh_relationship){
            return Generator\map(
                function($relationship) use ($operations) {
                    $new_relationship = apply_operations($relationship, $operations);
                    return [$new_relationship, $operations];
                },
                $fresh_relationship);
        });

!SLIDE

##Testable Friendship Tracer properties
- Friendship is symmetric Alice is friends with Bob means Bob is friends with Alice
- No self friending Alice can not be friends with herself
