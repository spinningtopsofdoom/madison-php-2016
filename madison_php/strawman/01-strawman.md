!SLIDE

# Strawman example
![Straw Man](../../images/straw_man.png)

!SLIDE

# Latin Filter
## Transforms the word "cat" to "Felinus"

    @@@ php
    latinify("cat") === "Felinus";

!SLIDE

# Basic unit tests for Latin filter

    @@@ php
    latinify("no felines here") === "no felines here";
    latinify("cat") === "Felinus";
    latinify("Where is the cat?") === "Where is the Felinus?";

!SLIDE

# Totally valid function

    @@@ php
    function latinify($plain_string) {
      if ($plain_string === "cat") {
        return "Felinus";
      } elseif ($plain_string === "Where is the cat?") {
        return  "Where is the Felinus?";
      }
      return $plain_string;
    }

!SLIDE

# Lets add more tests

    @@@ php
    latinify("fat cat") === "fat Felinus";
    latinify("cat with a hat") === "Felinus with a hat";

!SLIDE

    @@@ php
    function latinify($plain_string) {
      if ($plain_string === "cat") {
        return "Felinus";
      } elseif ($plain_string === "Where is the cat?") {
        return  "Where is the Felinus?";
      } elseif ($plain_string === "fat cat") {
        return  "fat Felinus";
      } elseif ($plain_string === "cat with a hat") {
        return  "Felinus with a hat";
      }
      return $plain_string;
    }

!SLIDE

## Now try property based testing

!SLIDE

# The output string should contain no "cat"s

    @@@ php
    substr_count(latinify($cat_string), "cat") === 0;

!SLIDE

# Our function is even easier to fake

    @@@ php
    function latinify($plain_string) {
      return  "No Felinus here :p";
    }

!SLIDE

## The output string has as many "Felinus" as the input has "cat"s

    @@@ php
    $cat_count = substr_count($cat, "cat");
    $feline_count = substr_count(latinify($cat), "Felinus");
    $cat_count === $feline_count;

!SLIDE

## Finally forced to create a reasonable function

    @@@ php
    function latinify($plain_string) {
      return str_replace("cat", "Felinus", $plain_string);
    }

!SLIDE

# What about "Hepcat on a catamaran"?
## No translation should take place

    @@@ php
    $cool_cat = "Hepcat on a catamaran"
    $cool_cat === latinify($cool_cat);

!SLIDE

## The number of words "Felinus" matches the number of sub strings "Felinus"

    @@@ php
    $feline= latinify($cat);
    $word_count = preg_match_all('/\bFelinus\b/', $feline);
    $substring_count = substr_count($feline, "Felinus");
    $word_count === $substr_count;

!SLIDE

## Final function only replaces "cat" when it is a word

    @@@ php
    function latinify($plain_string) {
      return preg_replace('/\bcat\b/', "Felinus", $plain_string);
    }
