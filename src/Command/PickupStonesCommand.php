<?php

/*
 * This file is part of Stones Task.
 *
 * (c) Dan Sorahan <dan@kyoto.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DJS\Command;

use DJS\Formatter\ResponseFormatter;
use DJS\Exception\IllegalHeapSizeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PickupStones
 * @package DJS\Command
 */
class PickupStonesCommand extends Command
{
    /**
     * Size of the initial heap.
     * @var int
     */
    protected $heap;

    /**
     * Flag determining if the game can be won.
     *
     * @var bool
     */
    protected $canWin = false;

    /**
     * Symfony console component configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('djs:stones')
             ->setDescription('Calculate if game can be won')
             ->SetHelp('Provide parameter for number of stones in pile at the start of the game to determine if the game can be won.')
             ->addArgument('heap', InputArgument::OPTIONAL, 'Number of stones in heap at the start of the game.');
    }

    /**
     * Main task runner for Symfony console component.
     * First check to see if a argument has been provided
     * to the command, if not, ask the user to specify the
     * heap size. Use the value provided to determine if
     * the game can be won. Finally, provide a formatted message
     * to the user telling them if they can win, and how to
     * proceed to ultimately win.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return ResponseFormatter $formatter
     * @throws IllegalHeapSizeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatter = new ResponseFormatter($input, $output);

        if ($input->getArgument('heap')) {
            $this->checkAbilityToWin($input->getArgument('heap'));
        } else {
            $formatter->title('Can I win?');

            $this->heap = $formatter->ask('How many stones are in the heap?', 10, function ($answer) {
                return $this->checkAbilityToWin($answer);
            });
        }

        if ($this->canWin) {
            return $formatter->winningGame(
                [
                    'You can win this game!',
                    'To win, make sure that after your turn a multiple of four is left over!'

                ]
            );
        }

        return $formatter->lostGame(
            [
                'Sorry, You can\'t win this game!',
                'To win, you must have a multiple of four left over, after each of your turns!'
            ]
        );
    }

    /**
     * Firstly check if the heap provided is numeric, if
     * so, check to see if heap is winnable. if no value
     * is provided (it never should not be) default to a
     * heap size of 10
     *
     * @param int $heap
     * @return bool
     * @throws IllegalHeapSizeException
     */
    public function checkAbilityToWin($heap = 10)
    {
        if ($this->isArgumentNumeric($heap)) {
            $this->isHeapDividend();
        }

        return $this->canWin;
    }

    /**
     * If a value is passed via the command parameters validate that
     * it is a positive whole number. We're using a preg_match for
     * this due to a quirk in Symfony's command interface whereby any
     * answer provided to a question is cast to a string, regardless
     * of content. Additionally this allows us to ensure that the
     * value is not signed, not a decimal.
     *
     * @param int $heap
     * @return int
     * @throws IllegalHeapSizeException
     */
    protected function isArgumentNumeric($heap)
    {
        if (!preg_match("'^[0-9]{1,}$'", $heap) || $heap <= 0) {
            throw new IllegalHeapSizeException();
        } else {
            $this->heap = $heap;
            return $this->heap;
        }
    }

    /**
     * The game consists of a simple algorithm to determine the winner.
     * If on the first turn a number of stones can be removed that
     * leaves a remainder that is a multiple of four, the player that goes
     * first is guaranteed to be the winner.
     *
     * Initially do a last minute check on the heap to ensure no stray
     * non-int values have slipped through the net (better safe than sorry)
     *
     * Check if the heap is divisible by four, if so it's possible
     * for the user to win the game.
     *
     * @return array|void
     * @throws IllegalHeapSizeException
     */
    protected function isHeapDividend()
    {
        $this->isArgumentNumeric($this->heap);

        if ($this->heap % 4 !== 0) {
            $this->canWin = true;
        }
    }
}
