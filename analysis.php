<?php

require_once __DIR__ . '/Reporter.php';
require_once __DIR__ . '/Files.php';
require_once __DIR__ . '/HistoryExtractor.php';

$projectDir = $argv[1];
$taskPattern = isset($argv[2]) ? $argv[2] : null;

$files = new Files();
$log = $files->gitLog($projectDir);

$extractor = new HistoryExtractor();
$project = $extractor->extract($log, $projectDir, $taskPattern);

$reporter = new Reporter();
$reporter->generate($project);