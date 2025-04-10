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

        $event_reflection = new ReflectionClass(Event::class);

        $eventLogger = $event_reflection->getProperty('eventLogger')
                                        ->getValue($event);

        $this->assertSame($this->class, $eventLogger);

        $point = $event_reflection->getProperty('point')
                                  ->getValue($event);

        $this->assertInstanceOf(Point::class, $point);

        $point_reflection = new ReflectionClass(Point::class);

        $measurement = $point_reflection->getProperty('measurement')
                                        ->getValue($point);

        $this->assertSame('event', $measurement);

        $defaultTags = $point_reflection->getProperty('tags')
                                        ->getValue($point);

        $this->assertSame($this->defaultTags, $defaultTags);
    }

}

?>
