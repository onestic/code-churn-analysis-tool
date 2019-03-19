<?php

require_once __DIR__ . '/ScriptArguments.php';
require_once __DIR__ . '/History.php';
require_once __DIR__ . '/GitRepository.php';
require_once __DIR__ . '/TaskFinder.php';
require_once __DIR__ . '/IndentationComplexityAnalyzer.php';
require_once __DIR__ . '/HtmlScatterPlot.php';

$arguments = new ScriptArguments($argv);

$projectHistory = new History(
    new GitRepository($arguments->projectDirectory()),
    new TaskFinder($arguments->projectTaskKey()),
    new IndentationComplexityAnalyzer(4)
);

$churnVsComplexity = new HtmlScatterPlot(
    $projectHistory->report(),
    __DIR__ . '/scatter-plot.html.template',
    __DIR__ . '/churn-vs-complexity.html'
);

$churnVsComplexity->render();