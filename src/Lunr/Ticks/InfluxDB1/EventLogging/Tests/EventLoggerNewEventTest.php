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
     * Test that new_event() returns an Event instance with default retention policy.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::new_event
     */
    public function testNewEventWithDefaultRetentionPolicy(): void
    {
        $event = $this->class->new_event('event');

        $this->assertInstanceOf(Event::class, $event);

        $event_reflection = new ReflectionClass(Event::class);

        $eventLogger = $event_reflection->getProperty('eventLogger')
                                        ->getValue($event);

        $this->assertSame($this->class, $eventLogger);

        $retentionPolicy = $event_reflection->getProperty('retentionPolicy')
                                        ->getValue($event);

        $this->assertNull($retentionPolicy);

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

    /**
     * Test that new_event() returns an Event instance with custom retention policy.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::new_event
     */
    public function testNewEventWithCustomRetentionPolicy(): void
    {
        $event = $this->class->new_event('event', '7d');

        $this->assertInstanceOf(Event::class, $event);

        $event_reflection = new ReflectionClass(Event::class);

        $eventLogger = $event_reflection->getProperty('eventLogger')
                                        ->getValue($event);

        $this->assertSame($this->class, $eventLogger);

        $retentionPolicy = $event_reflection->getProperty('retentionPolicy')
                                        ->getValue($event);

        $this->assertSame('7d', $retentionPolicy);

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
