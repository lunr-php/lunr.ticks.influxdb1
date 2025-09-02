<?php

/**
 * This file contains the ProfilerBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\Profiling\Tests;

use Lunr\Ticks\EventLogging\Null\NullEvent;
use Lunr\Ticks\EventLogging\Null\NullEventLogger;
use Lunr\Ticks\InfluxDB1\EventLogging\EventLogger;
use Lunr\Ticks\InfluxDB1\Profiling\Profiler;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;

/**
 * This class contains tests for the Profiler class.
 *
 * @covers Lunr\Ticks\InfluxDB1\Profiling\Profiler
 */
class ProfilerBaseTest extends ProfilerTestCase
{

    /**
     * Test that the Event class is passed correctly.
     */
    public function testEventIsPassedCorrectly(): void
    {
        $this->assertPropertySame('event', $this->event);
    }

    /**
     * Test that the fields array is initialized empty.
     */
    public function testFieldsIsInitializedEmpty(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('fields'));
    }

    /**
     * Test that the tags array is initialized empty.
     */
    public function testTagsIsInitializedEmpty(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('tags'));
    }

    /**
     * Test that the spans array is initialized empty.
     */
    public function testSpansIsInitializedEmpty(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('spans'));
    }

    /**
     * Test that the start timestamp is set.
     */
    public function testStartTimestampIsSet(): void
    {
        $this->assertPropertySame('startTimestamp', $this->startTimestamp);
    }

    /**
     * Test that the start timestamp is set.
     */
    public function testCustomStartTimestampIsSet(): void
    {
        $eventlogger = $this->getMockBuilder(EventLogger::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $eventlogger->expects($this->once())
                    ->method('newEvent')
                    ->with('foo')
                    ->willReturn($this->event);

        $startTimestamp = 1734352685.1645;

        $controller = $this->createMockForIntersectionOfInterfaces(
            [
                TracingControllerInterface::class,
                TracingInfoInterface::class,
            ]
        );

        $controller->expects($this->once())
                   ->method('getTraceId')
                   ->willReturn('e0af2cd4-6a1c-4bd6-8fca-d3684e699784');

        $controller->expects($this->once())
                   ->method('getSpanId')
                   ->willReturn('3f946299-16b5-44ee-8290-3f0fdbbbab1d');

        $class = new Profiler($eventlogger, $controller, 'foo', $startTimestamp);

        $property = $this->getReflectionProperty('startTimestamp');

        $this->assertSame($startTimestamp, $property->getValue($class));
    }

    /**
     * Test the profiler works correctly with a NullEventLogger.
     */
    public function testWithNullEventLogger(): void
    {
        $eventlogger = $this->getMockBuilder(NullEventLogger::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $event = new NullEvent($eventlogger);

        $eventlogger->expects($this->once())
                    ->method('newEvent')
                    ->with('foo')
                    ->willReturn($event);

        $controller = $this->createMockForIntersectionOfInterfaces(
            [
                TracingControllerInterface::class,
                TracingInfoInterface::class,
            ]
        );

        $controller->expects($this->once())
                   ->method('getTraceId')
                   ->willReturn('e0af2cd4-6a1c-4bd6-8fca-d3684e699784');

        $controller->expects($this->once())
                   ->method('getSpanId')
                   ->willReturn('3f946299-16b5-44ee-8290-3f0fdbbbab1d');

        $class = new Profiler($eventlogger, $controller, 'foo');

        $property = $this->getReflectionProperty('event');

        $this->assertSame($event, $property->getValue($class));
    }

}

?>
