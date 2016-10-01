# Madison PHP 2016 Eris Talk

This is the slides and code samples for my talk about Eris for Madison PHP 2016

## Setup

Eris is installed using [composer](https://getcomposer.org/)

Run `./scripts/setup_eris.sh` to setup Eris so the code samples will run.

If that does not work try running `composer install`

## Code samples

All code samples are located in the `example` directory

### Strawman (latinify) example

In this example we want a function (named `latinify`) that will translate the word `cat` in a string to `Felinus`.

`LatinifyShrinkingProperty.php` demonstrates a failing property test and the use of shrinking. Run it with `scripts/latinify_shrinking_property.sh`.  The file contains failing latinify functions for every property and all properties (`fails_every_case`, `fails_order_test`, `fails_only_replace_word_test`, `fails_matching_word_count`)

To how each function fails replace `fails_matching_word_count` in `latinfy` with the function you want to see (e.g. `fails_order_test`

```php
function latinify($plain_string) {
  return fails_order_test($plain_string);
}
```

`LatinifyPropertyTest.php` shows a correct `latinify` implementation passing all the property tests. Run it with `scripts/latinify_property_test.sh`.

`LatinifyGeneator.php` gives a `latinify` input generator. Run it with `scripts/latinify_generator.sh`.

### Temperature Generation example

This code sample (`TemperatureGenerator.php`) builds an array of ten temperature measurements of the form `['scale' => 'F', 'degrees' => 50]`

It goes through setting up a basic generator using composite generators (`frequency`, `map`, `bind`, and `suchThat`) to and more refinements to the temperature measurements.

### Property Testing Objects

Since property testing came from the functional programming world there is a lack of examples demonstrating it's use with objects.

This example will be testing a Friendship tracker object that keeps track of friendships between a group of people. Here's the objects methods

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

`FailedFriendPropertyTest.php` gives an example of failing property tests in an Object Oriented context. The incorrect state of the object is shown along with the operations that got it there. Run `scripts/friend_failing_property.sh` to see this failing example.

`FriendPropertyTest.php` is the same property tests this time with a correct Friendship tracker. Run `scripts/friend_property_test.sh` to see a working Friendship tracker pass property tests.

`FriendRelationshipGenerator.php` shows the steps to building a Friendship tracker generator. Run `scripts/friend_generator.sh` to see the generated output.

## Starting Points

`BaseGeneratorPlayground.php` contains all the setup needed to construct your own generators and run them to see their output. To run the file use the command `php example/BaseGeneratorPlayground.php`.

`BasePropertyTest.php` is the counterpart for property tests. It contains all the setup you need create and run your own property tests. `vendor/bin/phpunit BasePropertyTest.php` will run the property tests.

License

Copyright © 2016 Peter Schuck

Distributed under the MIT License.
