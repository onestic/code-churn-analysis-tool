<?php declare(strict_types=1);

class TaskFinder
{
    private $projectKey;

    public function __construct($projectKey)
    {
        $this->projectKey = $projectKey;
    }

    public function find($text)
    {
        $matches = [];

        preg_match('/' . $this->projectKey . '-(\d{1})/', $text, $matches);
        preg_match('/' . $this->projectKey . '-(\d{2})/', $text, $matches);
        preg_match('/' . $this->projectKey . '-(\d{3})/', $text, $matches);

        return $matches;
    }
}