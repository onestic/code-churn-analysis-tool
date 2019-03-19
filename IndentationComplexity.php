<?php declare(strict_types=1);

class  IndentationComplexity
{
    private $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function score() {
        $lineCount = 1;
        $totalLogicalIndentation = 1;

        $handle = fopen($this->filepath, "r");
        while(!feof($handle)){
            $line = fgets($handle);
            $lineCount++;
            if (false === $line) {
                continue;
            }
            $lineIndentationSize = strlen($line)-strlen(ltrim($line));
            if ($lineIndentationSize > 3) {
                $totalLogicalIndentation += ($lineIndentationSize/4);
            }
        }
        fclose($handle);

        return (int) $totalLogicalIndentation;
    }
};