<?php

namespace Minesweeper;

use Minesweeper\Error\BombExplodedException;
use Minesweeper\Error\GameNotStartedException;
use Minesweeper\Error\OutOfBoundException;

/**
 * Class Board
 * @package Minesweeper
 */
class Board
{

    /**
     *
     */
    const BOMB = 'X';

    /**
     * @var int
     */
    protected $length;
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $bombCount;
    /**
     * @var
     */
    protected $board;

    /**
     * Board constructor.
     *
     * @param int $length
     * @param int $width
     * @param int $bombs
     */
    public function __construct(int $length, int $width, int $bombs)
    {
        $this->length    = $length;
        $this->width     = $width;
        $this->bombCount = $bombs;
    }

    /**
     *
     */
    private function spreadBomb()
    {
        for ($i = 0; $i < $this->bombCount; $i++) {
            $x = mt_rand(0, $this->length - 1);
            $y = mt_rand(0, $this->width - 1);
            // If already placed bomb at coords, fallback one step back to get new coords
            if ($this->getCoord($x, $y) === self::BOMB) {
                // this ensures bomb count is fullfilled
                $i--;
                continue;
            }
            $this->board[$x][$y] = self::BOMB;
        }
    }

    /**
     *
     */
    public function start()
    {
        // create board of LxW
        $this->board = array_fill(0, $this->length, null);
        foreach ($this->board as &$row) {
            $row = array_fill(0, $this->width, null);
        }
        unset($row);
        $this->spreadBomb();
    }

    /**
     *
     */
    public function finish()
    {
        $this->board = null;
    }

    /**
     * @param $x
     * @param $y
     *
     * @throws BombExplodedException
     * @throws GameNotStartedException
     * @throws OutOfBoundException
     */
    public function selectCoord($x, $y)
    {
        if ( ! $this->board) {
            throw new GameNotStartedException();
        }

        if ($x < 0 || $x >= $this->length || $y < 0 || $y >= $this->width) {
            throw new OutOfBoundException();
        }
        if ($this->board[$x][$y] === self::BOMB) {
            throw new BombExplodedException();
        }

        // get bombs around coords
        if ($this->board[$x][$y] === null) {
            $bombAround = 0;
            for ($i = -1; $i <= 1; $i++) {
                for ($j = -1; $j <= 1; $j++) {
                    if ($i == 0 && $j == 0) {
                        continue;
                    }

                    $a = $x + $i;
                    $b = $y + $j;

                    $bombAround += $this->getCoord($a, $b) === self::BOMB ? 1 : 0;
                }
            }
            $this->board[$x][$y] = $bombAround;
        }
    }

    /**
     * @param $x
     * @param $y
     *
     * @return mixed
     */
    public function getCoord($x, $y)
    {
        return $this->board[$x][$y];
    }
}