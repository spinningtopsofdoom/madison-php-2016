<?php

use Eris\Generator;

function latinify($plain_string) {
  return preg_replace('/\bcat\b/', "Felinus", $plain_string);
}

class LatinifyTest extends \PHPUnit_Framework_TestCase
{
    use Eris\TestTrait;

    protected $cat_word_gen;

    public function setUp() {
      $non_cat_word = Generator\elements(['where', 'is', 'the', 'fat', 'with', 'a', 'on']);
      $cat_word = Generator\constant('cat');
      $cat_within_word = Generator\elements(['hepcat', 'catamaran']);

      $word_gen = Generator\frequency([10, $non_cat_word], [1, $cat_word], [1, $cat_within_word]);

      $this->cat_sentence_gen = Generator\map(
          function($words) { return implode($words, ' '); },
          Generator\seq($word_gen));
    }

    public function testOnlyReplaceWords()
    {
        $this->forAll($this->cat_sentence_gen)
            ->then(function ($cat_sentence) {
                $feline_sentence = latinify($cat_sentence);
                $word_count = preg_match_all('/\bFelinus\b/', $feline_sentence);
                $substring_count = substr_count($feline_sentence, "Felinus");
                //When test fails print out failing input
                if ($word_count !== $substring_count) {
                    echo "\n";
                    var_dump($cat_sentence);
                }

                $this->assertEquals($word_count, $substring_count,
                    "'{$feline_sentence}' has more 'Felinus' sub strings ({$substring_count}) when words ({$word_count})");
            }
        );
    }

    public function testMatchingWordCount()
    {
        $this->forAll($this->cat_sentence_gen)
            ->then(function ($cat_sentence) {
                $cat_word_count = preg_match_all('/\bcat\b/', $cat_sentence);

                $feline_sentence = latinify($cat_sentence);
                $felinus_word_count = preg_match_all('/\bFelinus\b/', $feline_sentence);

                $this->assertEquals($cat_word_count, $felinus_word_count,
                    "'{$cat_sentence}' ({$cat_word_count}) cat word count is not equal to '{$feline_sentence}' ({$felinus_word_count}) Felinus word count");
            }
        );
    }
    public function testReplacementOrdering()
    {
        $this->forAll($this->cat_sentence_gen)
            ->then(function ($cat_sentence) {
                $trimmed_cat = preg_replace('/\bcat\b/', '', $cat_sentence);

                $feline_sentence = latinify($cat_sentence);
                $trimmed_felinus = preg_replace('/\bFelinus\b/', '', $feline_sentence);

                $this->assertEquals($trimmed_cat, $trimmed_felinus,
                    "'{$feline_sentence}' has replaced 'cat' in '{$cat_sentence}' the wrong places");
            }
        );
    }
}
?>
