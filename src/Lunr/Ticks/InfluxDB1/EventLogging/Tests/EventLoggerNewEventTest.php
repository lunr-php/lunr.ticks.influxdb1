<?php

/**
 * This file contains the EventLoggerNewEventTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use InfluxDB\Point;
use Lunr\Ticks\InfluxDB1\EventLogging\Event;
use ReflectionClass;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger
 */
class EventLoggerNewEventTest extends EventLoggerTestCase
{

    /**
     * Test that newEvent() returns an Event instance.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::newEvent
     */
    public function testNewEvent(): void
    {
        $event = $this->class->newEvent('event');

        $this->assertInstanceOf(Event::class, $event);

        $eventReflection = new ReflectionClass(Event::class);

        $eventLogger = $eventReflection->getProperty('eventLogger')
                                       ->getValue($event);

        $this->assertSame($this->class, $eventLogger);

        $point = $eventReflection->getProperty('point')
                                 ->getValue($event);

        $this->assertInstanceOf(Point::class, $point);

        $pointReflection = new ReflectionClass(Point::class);

        $measurement = $pointReflection->getProperty('measurement')
                                       ->getValue($point);

        $this->assertSame('event', $measurement);

        $defaultTags = $pointReflection->getProperty('tags')
                                       ->getValue($point);

        $this->assertSame($this->defaultTags, $defaultTags);
    }

}

?>
