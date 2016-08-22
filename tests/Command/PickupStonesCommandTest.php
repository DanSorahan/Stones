<?php

/*
 * This file is part of Stones Task.
 *
 * (c) Dan Sorahan <dan@kyoto.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use DJS\Command\PickupStonesCommand;

/**
 * Tests for PickupStonesCommand class
 *
 * Class PickupStonesCommandTest
 */
class PickupStonesCommandTest extends PHPUnit_Framework_TestCase
{
    protected $pickupStonesCommand;

    /**
     * Run before all tests, apply class to accessible variable
     */
    public function setUp()
    {
        parent::setUp();
        $this->pickupStonesCommand = new PickupStonesCommand();
    }


    protected function tearDown()
    {
        unset($this->pickupStonesCommand);
    }

    /**
     * Ensure negative values cannot be used
     *
     * @expectedException \DJS\Exception\IllegalHeapSizeException
     */
    public function testNegativeValuesThrowException()
    {
        $this->pickupStonesCommand->checkAbilityToWin(-4);
    }

    /**
     * Ensure non whole numbers cannot be used
     *
     * @expectedException \DJS\Exception\IllegalHeapSizeException
     */
    public function testDecimalValuesThrowException()
    {
        $this->pickupStonesCommand->checkAbilityToWin(2.5);
    }

    /**
     * Ensure non-numeric values cannot be used
     *
     * @expectedException \DJS\Exception\IllegalHeapSizeException
     */
    public function testNonNumericValuesThrowException()
    {
        $this->pickupStonesCommand->checkAbilityToWin('five');
    }

    /**
     * Ensure numeric values passed as strings can be used
     */
    public function testNumericStringValuesDoNotThrowException()
    {
        $this->pickupStonesCommand->checkAbilityToWin('5');
    }

    /**
     * Ensure that heaps that are initially divisible by four
     * cannot be won
     */
    public function testHeapsDivisibleByFourAreNotWinnable()
    {
        $this->assertEquals(false, $this->pickupStonesCommand->checkAbilityToWin(4));
    }

    /**
     * Ensure that heaps that are not initially divisible by
     * four can be won
     */
    public function testHeapsNotDivisibleByFourAreWinnable()
    {
        $this->assertEquals(true, $this->pickupStonesCommand->checkAbilityToWin(5));
    }

    /**
     * Ensure that heaps less than four can be won
     */
    public function testPositiveIntegersUnderFourAreWinnable()
    {
        $this->assertEquals(true, $this->pickupStonesCommand->checkAbilityToWin(1));
        $this->assertEquals(true, $this->pickupStonesCommand->checkAbilityToWin(2));
        $this->assertEquals(true, $this->pickupStonesCommand->checkAbilityToWin(3));
    }
}