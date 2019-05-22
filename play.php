#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Minesweeper\Board;
use Minesweeper\Error\BombExplodedException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Exception;

/**
 * Class MinesweeperCommand
 */
class MinesweeperCommand extends Command
{
    /**
     * Console Command Name.
     *
     * @var string
     */
    protected static $defaultName = 'minesweeper';

    /**
     * @var
     */
    protected $question;

    /**
     * @var
     */
    protected $board;

    /**
     * Default board length.
     *
     * @var int
     */
    protected $length = 20;

    /**
     * Default board width.
     *
     * @var int
     */
    protected $width = 30;

    /**
     * Default bomb count for the game.
     *
     * @var int
     */
    protected $bombCount = 25;

    /**
     * To check if game started or not?
     *
     * @var bool
     */
    protected $started = false;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->board    = new Board($this->length, $this->width, $this->bombCount);
        $this->question = $this->getHelper('question');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            try {
                $this->boardProcess($input, $output);
            } catch (Exception $e) {
                $this->started = false;
                $this->printBoard($output);

                if ($e instanceof BombExplodedException) {
                    $output->writeln("\n\nKaboom!!! You Lost!");
                }

                $question = new ConfirmationQuestion("Do you wish to restart the game? (y/n) ", false);
                $ans      = $this->question->ask($input, $output, $question);
                if ( ! $ans) {
                    break;
                }
            }
        }
    }

    /**
     * Process the board.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws Exception
     */
    protected function boardProcess(InputInterface $input, OutputInterface $output)
    {
        if ( ! $this->started) {
            $this->board->start();
            $this->started = true;
        }

        $this->printBoard($output);
        $question = new Question("Please enter coordinates for selecting a cell e.g. 3,4 :\n");
        $coords   = $this->question->ask($input, $output, $question);
        $coords   = explode(',', $coords);

        [$x, $y] = $coords;

        if ( ! is_numeric($x) || ! is_numeric($y)) {
            $output->writeln('<error>Invalid coordinates</error>');
            throw new Exception();
        }
        $x = trim($x);
        $y = trim($y);

        $this->board->selectCoord($x, $y);
    }

    /**
     * Prints the board.
     *
     * @param OutputInterface $output
     */
    public function printBoard(OutputInterface $output)
    {
        $output->writeln('');
        for ($i = 0; $i < $this->length; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $block = $this->board->getCoord($i, $j);
                $output->write(($block !== null && ($block !== Board::BOMB || ! $this->started)) ? "$block " : '_ ');
            }
            $output->writeln('');
        }
    }
}

$application = new Application('minesweepercligame', '1.0.0');
$command     = new MinesweeperCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();