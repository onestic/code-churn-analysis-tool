<?php declare(strict_types=1);

class HtmlScatterPlot
{
    private $template;
    private $result;
    private $title;
    private $axeX;
    private $axeY;
    private $labels;

    public function __construct(
        $template,
        $result,
        $title = '',
        $axeX = [],
        $axeY = [],
        $labels = []
    ) {
        $this->template = $template;
        $this->result = $result;
        $this->title = $title;
        $this->axeX = $axeX;
        $this->axeY = $axeY;
        $this->labels = $labels;
    }

    public function render()
    {
        $htmTemplate = file_get_contents($this->template);
        $htmlContent = str_replace('{{axeX}}', json_encode($this->axeX), $htmTemplate);
        $htmlContent = str_replace('{{axeY}}', json_encode($this->axeY), $htmlContent);
        $htmlContent = str_replace('{{labels}}', json_encode($this->labels), $htmlContent);
        $htmlContent = str_replace('{{title}}', json_encode($this->title), $htmlContent);

        file_put_contents($this->result, $htmlContent);
    }
}