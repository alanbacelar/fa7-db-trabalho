<?php

class TimeHelper
{
    private $start = 0;
    private $stop = 0;
    private $description;
    private $tag;

    public function __construct($tag = '') {
        $this->tag = $tag;
    }

    public function start($description = '')
    {
        $this->description = $description;
        $this->start = microtime(true);
    }

    public function stop()
    {
        $this->stop = microtime(true);
        $this->show();
    }

    public function getExcutionTime() {
        return round($this->stop - $this->start, 4);
    }

    public function show() {
        echo "[$this->tag] " . $this->description . ': ' . $this->getExcutionTime() . ' segundos' . "\n";
    }
}
