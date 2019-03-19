<?php declare(strict_types=1);

class History
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function report()
    {
        $report = ['files' => []];
        $projectFiles = $this->project->filesThatHaveChangedMoreThan(2);

        foreach ($projectFiles as $filepath) {
            $report['files'][] = $this->fileHistory($filepath);
        }

        return $report;
    }

    public function fileHistory($filepath)
    {
        $tasks = [];
        $dates = [];
        $authors = [];

        $process = shell_exec("git -C " . $this->project->rootDirectory() . " log --all --date=short --pretty=format:'%H---%ad---%aN---%s' --no-renames --no-merges " . $filepath);

        $log = explode(PHP_EOL, $process);

        $commits = [];

        foreach ($log as $commitLine) {
            $commitDetails = explode('---', $commitLine);
            $dates[] = $commitDetails[1];
            $authors[] = $commitDetails[2];

            $commit = [
                'hash' => $commitDetails[0],
                'date' => $commitDetails[1],
                'author' => $commitDetails[2]
            ];

            $foundTasks = $this->findTasksInCommitMessage($commitDetails[3]);

            if (count($foundTasks) > 0) {
                $tasks[] = $foundTasks[0];
                $commit['tasks'] = $foundTasks[0];
            }

            $commits[] = $commit;
        }

        return [
            'filepath' => $filepath,
            'revisions' => count($commits),
            'active_days' => count(array_unique($dates)),
            'number_authors' => count(array_unique($authors)),
            'number_tasks' => count(array_unique($tasks)),
            'complexity' => $this->indentationComplexityScore($this->project->fullFilepath($filepath)),
            'authors' => implode(',', array_unique($authors)),
            'tasks' => implode(', ', array_unique($tasks)),
            'commits' => $commits
        ];
    }

    public function indentationComplexityScore($filepath)
    {
        return (new IndentationComplexity($filepath))->score();
    }

    public function findTasksInCommitMessage($message)
    {
        return (new TaskFinder($this->project->key()))->find($message);
    }
}