<?php

require_once __DIR__ . '/ScriptArguments.php';
require_once __DIR__ . '/Project.php';
require_once __DIR__ . '/TaskFinder.php';
require_once __DIR__ . '/History.php';
require_once __DIR__ . '/IndentationComplexity.php';
require_once __DIR__ . '/HtmlPlot.php';

$arguments = new ScriptArguments($argv);

$project = new Project($arguments->projectDir(), $arguments->projectKey());

$projectHistory = new History($project);

$churnVsComplexity = new HtmlPlot(
    $projectHistory->report(),
    __DIR__ . '/analysis.html.template',
    __DIR__ . '/analysis-result.html'
);

$churnVsComplexity->generate();