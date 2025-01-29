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
     * Test that get_name() gets the measurement name.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_name
     */
    public function testGetName(): void
    {
        $expected = 'event';

        $this->point->expects($this->once())
                    ->method('getMeasurement')
                    ->willReturn($expected);

        $value = $this->class->get_name();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_trace_id() gets the Trace ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_trace_id
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

        $value = $this->class->get_trace_id();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_span_id() gets the Span ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_span_id
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

        $value = $this->class->get_span_id();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_parent_span_id() gets the Span ID of the parent.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_parent_span_id
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

        $value = $this->class->get_parent_span_id();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_tags() gets the event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_tags
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

        $value = $this->class->get_tags();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_fields() gets the event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_fields
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

        $value = $this->class->get_fields();

        $this->assertSame($expected, $value);
    }

    /**
     * Test that get_timestamp() gets the event timestamp.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::get_timestamp
     */
    public function testGetTimestamp(): void
    {
        $expected = 1730723729;

        $this->point->expects($this->once())
                    ->method('getTimestamp')
                    ->willReturn($expected);

        $value = $this->class->get_timestamp();

        $this->assertSame($expected, $value);
    }

}

?>
