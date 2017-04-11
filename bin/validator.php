<?php
/** Validates a TriG, Turtle, N3, NQUADS or NTRIPLES file */
include_once(__DIR__ . '/../vendor/autoload.php');
use pietercolpaert\hardf\TriGParser;
use pietercolpaert\hardf\TriGWriter;
$format = "n3";
if (isset($argv[1]))
    $format = $argv[1];

$parser = new TriGParser(["format" => $format]);
$errored = false;
$finished = false;
$tripleCount = 0;
while (!$finished) {
    try {
        $line = fgets(STDIN);
        if ($line)
            $tripleCount += sizeof($parser->parseChunk($line));
        else {
            $tripleCount += sizeof($parser->end($line));
            $finished = true;
        }
    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
        $errored = true;
    }
}
if (!$errored) {
    echo "Parsed " . $tripleCount . " triples successfully.\n";
}