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

# Basic ERis Generators

    @@@ php
    Generator\nat(); // 3, 56, 1, 32
    Generator\string(); // "K", "g,", "jGHr38i"
    Generator\float(); // 3.87, 0.0, 1.7
    Generator\bool(); // true, true, false

!SLIDE

# Eris composite generator example
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

# Create a more realistic scale distribution

    @@@ php
	$fahrenhiet = Generator\constant('F');
	$celsius = Generator\constant('C');
	$kelvin = Generator\constant('K');
	$dist_scale = Generator\frequency(
       [6, $fahrenhiet],
       [3, $celsius],
       [1, $kelvin]);

!SLIDE

# Tempurature readings should match scale

	@@@ php
	$realistic_reading = Generator\bind(
	  $dist_scale,
	  function($scale) {
	  	$degrees = ['F' => Generator\choose(0, 100),
	  	  'C' => Generator\choose(0, 32),
	  	  'K' => Generator\choose(280, 310)];
	  	return Generator\associative(['scale' => $scale,
          'degrees' => $degrees[$scale]]);
	  }
	);
!SLIDE

# Temperature is accurate to the hundredths place

    @@@ php
	$accurate_reading = Generator\map(
		function($gen_data) {
		  list($precision, $reading) = $gen_data;
          $degrees = round($reading['degrees'] + $precision, 2);
		  $reading['degrees'] = $degrees;
		  return $reading;},
		Generator\tuple(Generator\float(), $realistic_reading));

!SLIDE

# Measurements have to have one Kelvin Reading

    @@@ php
	$measurements = Generator\vector(10, $accurate_reading);
	$one_k_measurements = Generator\suchThat(
		function ($measurements) {
			return array_reduce(
              $measurements,
              function($one_k, $reading) {
				return $one_k || ($reading['scale'] === 'K');
			}, false);
		},
		$measurements
	);
