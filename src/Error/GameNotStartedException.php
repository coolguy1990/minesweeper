<?php

namespace Minesweeper\Error;

class GameNotStartedException extends \Exception
{
    public function __construct($message = 'Game has not started yet')
    {
        parent::__construct($message);
    }
}