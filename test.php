<?php

use Minesweeper\Board;
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

    public function testBombCount() {
        $this->board->start();
        // get count of bombs
        $bombNumber = 0;
        for ($i = 0; $i < $this->length; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $coord = $this->board->getCoord($i, $j);

                if ($coord !== NULL) {
                    $bombNumber++;
                }
            }
        }

        $this->assertEquals($bombNumber, $this->bombCount);
    }
}