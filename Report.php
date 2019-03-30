<?php declare(strict_types=1);

class Report
{
    private $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function activeDays()
    {
        $activeDays = [];

        foreach ($this->report as $fileHistory) {
            $activeDays[] = $fileHistory['active_days'];
        }

        return $activeDays;
    }

    public function complexity()
    {
        $complexity = [];

        foreach ($this->report as $fileHistory) {
            $complexity[] = $fileHistory['complexity'];
        }

        return $complexity;
    }

    public function paths()
    {
        $paths = [];

        foreach ($this->report as $fileHistory) {
            $paths[] = $fileHistory['filepath'];
        }

        return $paths;
    }
}