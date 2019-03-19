<?php declare(strict_types=1);

class HtmlScatterPlot
{
    private $analysis;
    private $template;
    private $result;

    public function __construct(
        $analysis,
        $template,
        $result
    ) {
        $this->analysis = $analysis;
        $this->template = $template;
        $this->result = $result;
    }

    public function render()
    {
        $htmTemplate = file_get_contents($this->template);
        $jsonAnalysis = json_encode($this->analysis);
        $htmlContent = str_replace('{{analysis}}', $jsonAnalysis, $htmTemplate);

        file_put_contents($this->result, $htmlContent);
    }
}