!SLIDE

# Property Based Testing:
## Let the Computer Make Tests for You

&nbsp;
## Peter Schuck
## @bendyworks / @spinningtopsofdoom

!SLIDE

#Eris
##Library property testing

!SLIDE

##Typical Unit Tests

    @@@ php
    add2(3, 7) === 10;
    sort(["c", "a", "b"]) === ["a", "b", "c"];

!SLIDE

##Instead we test property that always holds

    @@@ php
    add2($a, $b) === add2($b, $a);
    sort($items) === sort(sort($items));

!SLIDE

##Properties are tested via generated inputs

    @@@ php
    $add2_generator = Generator\vector(2, Generator\int());
    $sort_generator = Generator\seq(Generator\string());

!SLIDE

# How is this useful
