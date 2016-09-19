!SLIDE

# How are the strings for `latinify` created?

!SLIDE

## Eris provides generators to create random inputs for tests
- Composable, you can build a bigger generator from smaller ones
- Repeatable, a failing input can be repeated
- Shrinkable, a generated input is shrunk to the smallest failure

!SLIDE

#What is shrinking?

!SLIDE

##Find the misspelled word

Deductibles are typically used to deter the large number of claims that a consumer can be reasonibly expected to bear the cost of. By restricting its coverage to events that are significant enough to incur large costs, the insurance firm expects to pay out slightly smaller amounts much less frequently, incurring much higher savings.


!SLIDE

Deductibles are typically used to deter the large number of claims that a consumer can be reasonibly expected to bear the cost of.

!SLIDE

that a consumer can be reasonibly expected to bear the cost of.

!SLIDE

that a consumer can be reasonibly

!SLIDE

##Shrinking takes a failing input and finds the smallest portion of it that fails the test

reasonibly

!SLIDE

##Primitives generators are well documented and easy to use

    @@@ php
    Generator\nat(); // 3, 56, 1, 32
    Generator\string(); // "K", "g,", "jGHr38i"
    Generator\float(); // 3.87, 0.0, 1.7
    Generator\bool(); // true, true, false

!SLIDE

## Two Eris composite generators examples

!SLIDE

##Collection of 10 temperature readings

Each reading looks like `['scale' => 'F', 'degress' => 50]`

!SLIDE

#Basic setup

    @@@ php
    $scale = Generator\elements(['C', 'F']);
    $degrees = Generator\choose(-100, 100));
    $reading = Generator\associative([
      'scale' => $scale,
      'degrees' => $degrees]);
    $measurements = Generator\vector(10, $reading);

!SLIDE

# The odds are 3 "F"s for every "C"

    @@@ php
    $fahrenhiet = Generator\constant('F');
    $celsius = Generator\constant('C');
    $three_f_scale = Generator\frequency(
      [3, $fahrenheit],
      [1, $celsius]);

!SLIDE

# Temperature is accurate to the hundredths place

    @@@ php
    $accurate_degrees = Generator\map(
        function($d) {
          list($base, $precision) = $d;
          return round(($base + $precision), 2);},
        Generator\tuple(Generator\float(), $degrees));

!SLIDE

# Pickup some items from messy room
## Each item is `[<type>, <item>]`

![Messy Room](../../images/messy_room.png)

!SLIDE

##Messy Room Items and types

    @@@ php
    $types = ['school', 'clothing', 'food'];
    $type_to_item = [
            'school' => ['notebook', 'pencil'],
            'clothing' => ['shirt', 'hat'],
            'food' => ['chips', 'apple']];

!SLIDE

#Generate type then find item from type

    @@@ php
    $type = Generator\elements($types);
    $type_and_item = Generator\bind($type,
        function($type) use ($type_to_item){
            return Generator\tuple(
                Generator\constant($type),
                Generator\elements($type_to_item[$type]));
        });
    $items_picked_up = Generator\seq($type_and_item);

!SLIDE

#Generator For straw man example

!SLIDE

##Word generators

    @@@ php
    $non_cat_word = Generator\elements(['where', 'is', 'the', 'fat', 'with', 'a', 'on']);
    $cat_word = Generator\constant('cat');
    $cat_within_word = Generator\elements(['hepcat', 'catamaran']);

!SLIDE

##Make the words into a sentence

    @@@ php
    $word_gen = Generator\frequency([10, $non_cat_word], [1, $cat_word], [1, $cat_within_word]);
    $cat_sentence_gen = Generator\map(
        function($words) { return implode($words, ' '); },
        Generator\seq($word_gen));
