!SLIDE

#Eris improvements

!SLIDE

#Shinking is currently very poor it it linear time
## Hypothesis (A Python Property Testing Library) has done a lot of work to making shrinking logarithmic time

!SLIDE

#Highly coupled with PHPUnit
##Can't play around with generators without arcane boilerplate

    @@@ php
    require 'vendor/autoload.php';
    Eris\TestTrait::erisSetupBeforeClass();
    use Eris\Generator;

    function sample($generator, $times, $seed = 10) {
        mt_srand($seed);
        return Eris\Sample::of($generator, 'mt_rand')
                 ->repeat($times)
                 ->collected();
    }

!SLIDE

#Spotty Documentation
##Great for simple cases, complex use cases sorely lacking

!SLIDE

#Property based testing is not a silver bullet
##It works best when mixed with unit tests


!SLIDE

##Unit tests are faster to write and run, closer to how the code is used and more anecdotal

!SLIDE

##Property tests are more resilient to change, have a much better coverage to size ratio, and give more concise description of model

!SLIDE

#Together they change your testing from soft science fiction to hard science fiction

!SLIDE

#Questions?

!SLIDE

#Thanks!
