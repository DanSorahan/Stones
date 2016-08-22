<?php

/*
 * This file is part of Stones Task.
 *
 * (c) Dan Sorahan <dan@kyoto.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DJS\Formatter;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Formatter for our game specific messages
 *
 * Class ResponseFormatter
 * @package DJS\Formatter
 */
class ResponseFormatter extends SymfonyStyle
{

    /**
     * Extend the default response block to format for a
     * winning game message, green text on default background.
     *
     * @param array|string $message
     * @return ResponseFormatter
     */
    public function winningGame($message)
    {
        $this->block($message, 'Yes!', 'fg=green', ' ', true);
    }

    /**
     * Extend the default response block to format for a
     * losing game message, white text on red background.
     *
     * @param array|string $message
     * @return ResponseFormatter
     */
    public function lostGame($message)
    {
        $this->block($message, 'No!', 'fg=white;bg=red', ' ', true);
    }
}
