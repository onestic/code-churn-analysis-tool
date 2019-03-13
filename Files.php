<?php declare(strict_types=1);

class Files
{
    public function gitLog($projectDir)
    {
        $process = shell_exec("git -C " . $projectDir . " log --format=format: --name-only | egrep -v '^$' | sort | uniq -c | sort -r | sort -k1 -n | awk '$1 > 2'");

        $log = explode(PHP_EOL, $process);

        return $log;
    }
}