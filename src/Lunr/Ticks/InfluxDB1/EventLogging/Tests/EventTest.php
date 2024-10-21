<?php

/**
 * This file contains the EventTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use InfluxDB\Point;
use Lunr\Halo\LunrBaseTest;
use Lunr\Ticks\InfluxDB1\EventLogging\Event;
use Lunr\Ticks\InfluxDB1\EventLogging\EventLogger;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the Event class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event
 */
abstract class EventTest extends LunrBaseTest
{

    /**
     * Mock instance of an InfluxDB Point
     * @var Point
     */
    protected Point $point;

    /**
     * Mock instance of the EventLogger class.
     * @var EventLogger
     */
    protected EventLogger $eventLogger;

    /**
     * Instance of the tested class.
     * @var Event
     */
    protected Event $class;

    /**
     * Testcase Constructor.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->point = $this->getMockBuilder(Point::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->eventLogger = $this->getMockBuilder(EventLogger::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->class = new Event($this->eventLogger, $this->point, '7d');

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->point);
        unset($this->eventLogger);
        unset($this->class);
    }

}

?>
