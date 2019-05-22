<?php

namespace Minesweeper\Error;

class OutOfBoundException extends \Exception
{
    public function __construct($message = 'Coords entered are not in range')
    {
        parent::__construct($message);
    }
}