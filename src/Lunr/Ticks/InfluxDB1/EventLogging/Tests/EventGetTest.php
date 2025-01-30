<?php

/**
 * This file contains the EventGetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event
 */
class EventGetTest extends EventTestCase
{

    /**
     * Test that getName() gets the measurement name.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getName
     */
    public function testGetName(): void
    {
        $expected = 'event';

        $this->point->expects($this->once())
                    ->method('getMeasurement')
                    ->willReturn($expected);

        $value = $this->class->getName();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getTraceId() gets the Trace ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getTraceId
     */
    public function testGetTraceId(): void
    {
        $expected = '4e122973-b870-471a-a00e-6a2778244738';

        $this->point->expects($this->once())
                    ->method('getFields')
                    ->willReturn([
                        'foo'     => 'bar',
                        'traceID' => $expected,
                        'baz'     => '100',
                    ]);

        $value = $this->class->getTraceId();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getSpanId() gets the Span ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getSpanId
     */
    public function testGetSpanId(): void
    {
        $expected = '4e122973-b870-471a-a00e-6a2778244738';

        $this->point->expects($this->once())
                    ->method('getFields')
                    ->willReturn([
                        'foo'     => 'bar',
                        'spanID' => $expected,
                        'baz'     => 100,
                    ]);

        $value = $this->class->getSpanId();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getParentSpanId() gets the Span ID of the parent.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getParentSpanId
     */
    public function testGetParentSpanId(): void
    {
        $expected = '4e122973-b870-471a-a00e-6a2778244738';

        $this->point->expects($this->once())
                    ->method('getFields')
                    ->willReturn([
                        'foo'          => 'bar',
                        'parentSpanID' => $expected,
                        'baz'          => 100,
                    ]);

        $value = $this->class->getParentSpanId();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getTags() gets the event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getTags
     */
    public function testGetTags(): void
    {
        $expected = [
            'foo' => 'bar',
            'baz' => '100',
        ];

        $this->point->expects($this->once())
                    ->method('getTags')
                    ->willReturn($expected);

        $value = $this->class->getTags();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getFields() gets the event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getFields
     */
    public function testGetFields(): void
    {
        $expected = [
            'foo' => 'bar',
            'baz' => 100,
        ];

        $this->point->expects($this->once())
                    ->method('getFields')
                    ->willReturn($expected);

        $value = $this->class->getFields();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that getTimestamp() gets the event timestamp.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::getTimestamp
     */
    public function testGetTimestamp(): void
    {
        $expected = 1730723729;

        $this->point->expects($this->once())
                    ->method('getTimestamp')
                    ->willReturn($expected);

        $value = $this->class->getTimestamp();

        $this->assertSame($expected, $value);
    }

}

?>
