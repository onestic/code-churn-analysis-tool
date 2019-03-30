<?php declare(strict_types=1);

class  IndentationComplexityAnalyzer
{
    private $indentationSize;

    public function __construct($indentationSize)
    {
        $this->indentationSize = $indentationSize;
    }

    public function score($filepath) {
        $lineCount = 1;
        $totalLogicalIndentation = 1;

        $handle = fopen($filepath, "r");
        while(!feof($handle)){
            $line = fgets($handle);
            $lineCount++;
            if (false === $line) {
                continue;
            }
            $lineIndentationSize = strlen($line)-strlen(ltrim($line));
            if ($lineIndentationSize > 3) {
                $totalLogicalIndentation += ($lineIndentationSize/$this->indentationSize);
            }
        }
        fclose($handle);

        return (int) $totalLogicalIndentation;
    }
};