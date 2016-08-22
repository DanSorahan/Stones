<?php

/*
 * This file is part of Stones Task.
 *
 * (c) Dan Sorahan <dan@kyoto.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DJS\Exception;

/**
 * Thrown if the provided heap size is either not
 * a numeric value, or if the value provided is negative.
 *
 * Class InvalidHeapSizeException
 * @package DJS\Exception
 */
class IllegalHeapSizeException extends \Exception
{
    protected $message = 'Number of stones in the heap should be a positive whole number.';
}
