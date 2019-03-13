<?php declare(strict_types=1);

class Reporter
{
    public function generate($project)
    {
        $htmTemplate = file_get_contents(__DIR__ . '/analysis.html.template');
        $jsonAnaysis = json_encode($project);
        $htmlContent = str_replace('{{analysis}}', $jsonAnaysis, $htmTemplate);
        file_put_contents("analysis-result.html", $htmlContent);
    }
}