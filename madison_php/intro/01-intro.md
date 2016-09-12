!SLIDE

#Property Based Testing:
##Let the Computer Make Tests for You

&nbsp;
## @spinningtopsofdoom

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

#Large costs
- Properties take longer to think of and make test for
- Generator of inputs is needed
- Tests take longer to run

!SLIDE

#Makes testing more of a science
- Testing deep properties instead of surface features
- Running hundreds or thousands of experiments
- Inputs are randomly generated

!SLIDE

#Unit testing is soft science fiction
![Science](../../images/soft_science_fiction.png)

!SLIDE

#Property testing does no to get us to science
![Science](../../images/science.png)

!SLIDE

It does get us to hard science fiction
![Science](../../images/hard_science_fiction.png)
