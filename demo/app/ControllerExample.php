<?php

namespace Tpab\Demo;

class ControllerExample
{
    private $teste;
    public function __construct($teste=null)
    {
        $this->teste=$teste;
    }

    public function index()
    {
        return "Testing Controller Without Method Parameters " . $this->teste;
    }

    public function test($test=null)
    {
        return 'Testing Controller With Method Parameters ' . $test . ' '. $this->teste;
    }
}
