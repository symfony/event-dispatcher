<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\EventDispatcher\Tests;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Test class for Event.
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\EventDispatcher\Event
     */
    protected $event;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var int
     */
    protected $eventDispatchedLineNumber;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->event = new Event;
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->event = null;
        $this->eventDispatcher = null;
    }

    public function testIsPropagationStopped()
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }

    public function testStopPropagationAndIsPropagationStopped()
    {
        $this->event->stopPropagation();
        $this->assertTrue($this->event->isPropagationStopped());
    }

    public function testSetDispatcher()
    {
        $this->event->setDispatcher($this->dispatcher);
        $this->assertSame($this->dispatcher, $this->event->getDispatcher());
    }

    public function testGetDispatcher()
    {
        $this->assertNull($this->event->getDispatcher());
    }

    public function testGetName()
    {
        $this->assertNull($this->event->getName());
    }

    public function testSetName()
    {
        $this->event->setName('foo');
        $this->assertEquals('foo', $this->event->getName());
    }

    public function testGetOriginator()
    {
        $this->dispatcher->addListener('foo', array($this, 'checkReceivedEvent'));
        $this->eventDispatchedLineNumber = __LINE__; $this->dispatcher->dispatch('foo', $this->event);
    }

    public function checkReceivedEvent($event)
    {
        $originatorData = $event->getOriginatorData();

        $this->assertSame($this, $originatorData['object']);
        $this->assertEquals(__FILE__, $originatorData['file']);
        $this->assertEquals($this->eventDispatchedLineNumber, $originatorData['line']);
    }
}


/**
 * The following code tests when an event is dispatched from global scope
 * rather than from an object. This is a rare, but somewhat annoying case,
 * because of the vagaries of PHP's debug_backtrace function.
 */

global $eventDispatcher;
global $eventDispatchedLineNumber;

$eventDispatcher = new EventDispatcher;

function dispatchEvent()
{
    global $eventDispatcher;
    global $eventDispatchedLineNumber;

    $eventDispatchedLineNumber = __LINE__; $eventDispatcher->dispatch('something');
}

$eventDispatcher->addListener('something', function($event) {
    global $eventDispatchedLineNumber;

    $originatorData = $event->getOriginatorData();
    \PHPUnit_Framework_TestCase::assertEquals(1, 1);
    \PHPUnit_Framework_TestCase::assertEquals(__FILE__, $originatorData['file']);
    \PHPUnit_Framework_TestCase::assertEquals($eventDispatchedLineNumber, $originatorData['line']);
});

dispatchEvent();
