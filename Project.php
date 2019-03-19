<?php declare(strict_types=1);

class Project
{
    private $rootDirectory;
    private $key;

    public function __construct($rootDirectory, $key)
    {
        $this->rootDirectory = $rootDirectory;
        $this->key = $key;
    }

    public function filesThatHaveChangedMoreThan($times = 1)
    {
        $process = shell_exec("git -C " . $this->rootDirectory . " log --format=format: --name-only | egrep -v '^$' | sort | uniq -c | sort -r | sort -k1 -n | awk '$1 > " . $times . "'");

        $log = explode(PHP_EOL, $process);

        $files = [];

        foreach ($log as $line) {
            $filepath = substr($line,8);
            $revisions = trim(substr($line,0,8));

            if (empty($revisions) || empty($filepath)) {
                continue;
            }

            if (!$this->hasFile($filepath)) {
                continue;
            }

            $files[] = $filepath;
        }

        return $files;
    }

    public function rootDirectory()
    {
        return $this->rootDirectory;
    }

    public function key()
    {
        return $this->key;
    }

    public function hasFile($filepath)
    {
        return file_exists($this->rootDirectory() . '/' . $filepath);
    }

    public function isCodeFile($filepath)
    {
        return strpos($filepath, ".") !== false;
    }

    public function fullFilepath($filepath)
    {
        return $this->rootDirectory() . "/" . $filepath;
    }
}