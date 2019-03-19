<?php declare(strict_types=1);

class ScriptArguments
{
    private $arguments;

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    public function projectDirectory()
    {
        return $this->arguments[1];
    }

    public function projectTaskKey()
    {
        return isset($this->arguments[2]) ? $this->arguments[2] : null;
    }
}