<?php declare(strict_types=1);

class History
{
    private $projectRepository;
    private $taskFinder;
    private $complexityAnalyzer;

    public function __construct(
        GitRepository $projectRepository,
        TaskFinder $taskFinder,
        IndentationComplexityAnalyzer $complexityAnalyzer
    ) {
        $this->projectRepository = $projectRepository;
        $this->taskFinder = $taskFinder;
        $this->complexityAnalyzer = $complexityAnalyzer;
    }

    public function report()
    {
        $report = [];
        $projectFiles = $this->projectRepository->filesThatHaveChangedMoreThan(2);

        foreach ($projectFiles as $filepath) {
            $report[] = $this->fileHistory($filepath);
        }

        return new Report($report);
    }

    public function fileHistory($filepath)
    {
        $tasks = [];
        $dates = [];
        $authors = [];

        $commits = $this->projectRepository->fileCommits($filepath);

        foreach ($commits as $commit) {
            $dates[] = $commit['date'];
            $authors[] = $commit['author'];

            $foundTasks = $this->taskFinder->find($commit['message']);

            if (count($foundTasks) > 0) {
                $tasks[] = $foundTasks[0];
                $commit['tasks'] = $foundTasks[0];
            }
        }

        return [
            'filepath' => $filepath,
            'revisions' => count($commits),
            'active_days' => count(array_unique($dates)),
            'number_authors' => count(array_unique($authors)),
            'number_tasks' => count(array_unique($tasks)),
            'complexity' => $this->complexityAnalyzer->score($filepath),
            'authors' => implode(',', array_unique($authors)),
            'tasks' => implode(', ', array_unique($tasks)),
            'commits' => $commits
        ];
    }
}
