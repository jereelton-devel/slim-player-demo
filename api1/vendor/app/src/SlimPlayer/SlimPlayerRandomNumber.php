<?php

namespace SlimPlayer;

class SlimPlayerRandomNumber
{

    private $randNumberPlayer;

    public function __construct()
    {
        $this->randNumberPlayer = rand(1, 10);
    }

    public function getRandomNumber()
    {
        return $this->randNumberPlayer;
    }

}

?>
