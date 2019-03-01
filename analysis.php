<?php

$project = ['files' => []];

$projectDir = $argv[1];

$taskPattern = isset($argv[2]) ? $argv[2] : null;

$process = shell_exec("git -C " . $projectDir . " log --format=format: --name-only | egrep -v '^$' | sort | uniq -c | sort -r | sort -k1 -n | awk '$1 > 2'");

$log = explode(PHP_EOL, $process);

foreach ($log as $line) {
    $filepath = substr($line,8);
    $revisions = trim(substr($line,0,8));

    if (empty($revisions) || empty($filepath)) {
        continue;
    }

    if (!file_exists($projectDir . '/' . $filepath)) {
        continue;
    }

    $tasks = [];
    $dates = [];
    $authors = [];

    $process = shell_exec("git -C " . $projectDir . " log --all --date=short --pretty=format:'%H---%ad---%aN---%s' --no-renames --no-merges " . $filepath);

    $log = explode(PHP_EOL, $process);

    if (!file_exists($projectDir . "/" . $filepath)) {
        continue;
    }

    if (!strpos($filepath, ".") !== false) {
        continue;
    }

    //Complexity compute
    $lineCount = 1;
    $totalLogicalIndentation = 1;
    $handle = fopen($projectDir . "/" . $filepath, "r");
    while(!feof($handle)){
        $line = fgets($handle);
        $lineCount++;
        $lineIndentationSize = strlen($line)-strlen(ltrim($line));
        if ($lineIndentationSize > 3) {
            $totalLogicalIndentation += ($lineIndentationSize/4);
        }
    }
    fclose($handle);

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

        $matches = [];

        preg_match('/' . $taskPattern . '-(\d{1})/', $commitDetails[3], $matches);
        preg_match('/' . $taskPattern . '-(\d{2})/', $commitDetails[3], $matches);
        preg_match('/' . $taskPattern . '-(\d{3})/', $commitDetails[3], $matches);

        if (count($matches) > 0) {
            $tasks[] = $matches[0];
            $commit['tasks'] = $matches[0];
        }

        $commits[] = $commit;
    }

    $project['files'][] = [
        'filepath' => $filepath,
        'revisions' => $revisions,
        'active_days' => count(array_unique($dates)),
        'number_authors' => count(array_unique($authors)),
        'number_tasks' => count(array_unique($tasks)),
        'complexity' => (int) $totalLogicalIndentation,
        'authors' => implode(',', array_unique($authors)),
        'tasks' => implode(', ', array_unique($tasks)),
        'commits' => $commits
    ];
}

$htmTemplate = file_get_contents(__DIR__ . '/analysis.html.template');

$jsonAnaysis = json_encode($project);
$htmlContent = str_replace('{{analysis}}', $jsonAnaysis, $htmTemplate);

file_put_contents("analysis-result.html", $htmlContent);