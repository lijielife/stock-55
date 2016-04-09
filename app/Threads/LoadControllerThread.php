<?php

namespace App\Threads;

class LoadControllerThread extends Thread
{
    private $loadController;
    private $masSymbols;
    private $statusArr;

    public function __construct($loadController, $masSymbols, $statusArr)
    {
        $this->loadController = $loadController;
        $this->masSymbols = $masSymbols;
        $this->statusArr = $statusArr;
    }

    public function run()
    {
        $this->loadController->executeByThread($this->masSymbols, $this->statusArr);
    }
}