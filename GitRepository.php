<?php declare(strict_types=1);

class GitRepository
{
    private $currentWorkingDirectory;

    public function __construct($currentWorkingDirectory)
    {
        $this->currentWorkingDirectory = $currentWorkingDirectory;
    }

    public function filesThatHaveChangedMoreThan($times)
    {
        $process = shell_exec("git -C " . $this->currentWorkingDirectory . " log --format=format: --name-only | egrep -v '^$' | sort | uniq -c | sort -r | sort -k1 -n | awk '$1 > " . $times . "'");

        $log = explode(PHP_EOL, $process);

        $files = [];

        foreach ($log as $line) {
            $filepath = substr($line,8);
            $revisions = trim(substr($line,0,8));

            if (empty($revisions) || empty($filepath)) {
                continue;
            }

            if (!file_exists($this->currentWorkingDirectory . '/' . $filepath)) {
                continue;
            }

            $files[] = $this->currentWorkingDirectory . '/' . $filepath;
        }

        return $files;
    }

    public function fileCommits($filepath)
    {
        $process = shell_exec("git -C " . $this->currentWorkingDirectory . " log --all --date=short --pretty=format:'%H---%ad---%aN---%s' --no-renames --no-merges " . $filepath);

        $log = explode(PHP_EOL, $process);

        $commits = [];

        foreach ($log as $line) {

            $commitDetails = explode('---', $line);

            $commit = [
                'hash' => $commitDetails[0],
                'date' => $commitDetails[1],
                'author' => $commitDetails[2],
                'message' => $commitDetails[3]
            ];

            $commits[] = $commit;
        }

        return $commits;
    }
}
