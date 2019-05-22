<?php

namespace Minesweeper\Error;

class BombExplodedException extends \Exception
{
    public function __construct($message = 'Kaboom!!! You Lost!')
    {
        parent::__construct($message);
    }
}