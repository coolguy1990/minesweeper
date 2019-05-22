<?php

use Minesweeper\Board;
use Minesweeper\Error\BombExplodedException;
use Minesweeper\Error\GameNotStartedException;
use Minesweeper\Error\OutOfBoundException;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public $board;
    protected $length = 20;
    protected $width = 30;
    protected $bombCount = 25;

    public function setUp(): void
    {
        parent::setUp();
        $this->board = new Board($this->length, $this->width, $this->bombCount);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->board->finish();
    }

    public function testBoardInstantiated()
    {
        $this->assertInstanceOf(Board::class, $this->board);
    }

    public function testBombCount()
    {
        $this->board->start();
        // get count of bombs
        $bombNumber = 0;
        for ($i = 0; $i < $this->length; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $coord = $this->board->getCoord($i, $j);

                if ($coord !== null) {
                    $bombNumber++;
                }
            }
        }

        $this->assertEquals($bombNumber, $this->bombCount);
    }

    public function testOutOfBoundExceptionThrown()
    {
        $this->expectException(OutOfBoundException::class);
        $this->board->start();
        $this->board->selectCoord(200, 100);
    }

    public function testGameNotStartedExceptionThrown()
    {
        $this->expectException(GameNotStartedException::class);
        $this->board->start();
        $this->board->finish();
        $this->board->selectCoord(10, 10);
    }

    public function testBombExplodedExceptionThrown()
    {
        $this->expectException(BombExplodedException::class);
        $this->board->start();
        while (true) {
            $x = mt_rand(0, $this->length - 1);
            $y = mt_rand(0, $this->width - 1);
            $this->board->selectCoord($x, $y);
        }
    }
}