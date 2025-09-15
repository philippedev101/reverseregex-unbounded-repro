<?php
require __DIR__ . "/vendor/autoload.php";

use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\SimpleRandom;

// The regex that fails due to the unbounded "*" quantifier
$expression = "[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*";

echo "Attempting to generate string for regex: " . $expression . PHP_EOL;
echo "Using SimpleRandom generator." . PHP_EOL;

try {
    $lexer = new Lexer($expression);
    // Seed with a constant for consistent results.
    // The seed itself doesn't matter for this crash.
    $gen = new SimpleRandom(123);
    $parser = new Parser($lexer, new Scope(), new Scope());
    
    $result = null;
    // This is the line that will crash.
    $parser->parse()->getResult()->generate($result, $gen);

    echo "Successfully generated: " . $result . PHP_EOL;
} catch (\Throwable $e) {
    echo "\nSuccessfully reproduced the crash!" . PHP_EOL;
    echo "Caught exception: " . $e->getMessage() . PHP_EOL;
    // echo $e->getTraceAsString() . PHP_EOL;
}
