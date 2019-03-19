<?php declare(strict_types=1);

class HtmlPlot
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

    public function generate()
    {
        $htmTemplate = file_get_contents($this->template);
        $jsonAnaysis = json_encode($this->analysis);
        $htmlContent = str_replace('{{analysis}}', $jsonAnaysis, $htmTemplate);

        file_put_contents($this->result, $htmlContent);
    }
}