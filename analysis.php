<?php

require_once __DIR__ . '/ScriptArguments.php';
require_once __DIR__ . '/History.php';
require_once __DIR__ . '/Report.php';
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

$filesReport = $projectHistory->report();

$churnVsComplexity = new HtmlScatterPlot(
    __DIR__ . '/scatter-plot.html.template',
    __DIR__ . '/churn-vs-complexity.html',
    'Active Days vs Complexity',
    $filesReport->activeDays(),
    $filesReport->complexity(),
    $filesReport->paths()
);

$churnVsComplexity->render();