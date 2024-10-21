<?php

/**
 * This file contains the EventSetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use Lunr\Ticks\Precision;
use RuntimeException;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event
 */
class EventSetTest extends EventTest
{

    /**
     * Unit test data provider for timestamps.
     *
     * @return array<string, array{0: Precision, 1: int|float}>
     */
    public function timestampProvider(): array
    {
        $data = [];

        $data['hours']        = [ Precision::Hours, 480757 ];
        $data['minutes']      = [ Precision::Minutes, 28845395 ];
        $data['seconds']      = [ Precision::Seconds, 1730723729 ];
        $data['milliseconds'] = [ Precision::MilliSeconds, 1730723729161 ];
        $data['microseconds'] = [ Precision::MicroSeconds, 1730723729161300 ];
        $data['nanoseconds']  = [ Precision::NanoSeconds, 1730723729161388499 ];

        return $data;
    }

    /**
     * Test that set_name() sets the measurement name.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_name
     */
    public function testSetName(): void
    {
        $this->point->expects($this->once())
                    ->method('setMeasurement')
                    ->with('event');

        $this->class->set_name('event');
    }

    /**
     * Test that set_trace_id() sets the Trace ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_trace_id
     */
    public function testSetTraceId(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'traceID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->set_trace_id('4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that set_span_id() sets the Span ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_span_id
     */
    public function testSetSpanId(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'spanID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->set_span_id('4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that set_tags() sets the event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_tags
     */
    public function testSetTags(): void
    {
        $tags = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('setTags')
                    ->with($tags);

        $this->class->set_tags($tags);
    }

    /**
     * Test that add_tags() adds event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::add_tags
     */
    public function testAddTags(): void
    {
        $tags = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('addTags')
                    ->with($tags);

        $this->class->add_tags($tags);
    }

    /**
     * Test that set_fields() sets the event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_fields
     */
    public function testSetFields(): void
    {
        $fields = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('setFields')
                    ->with($fields);

        $this->class->set_fields($fields);
    }

    /**
     * Test that add_fields() adds event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::add_fields
     */
    public function testAddFields(): void
    {
        $fields = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with($fields);

        $this->class->add_fields($fields);
    }

    /**
     * Test that set_timestamp() sets the event time.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::set_timestamp
     */
    public function testSetTimestamp(): void
    {
        $this->point->expects($this->once())
                    ->method('setTimestamp')
                    ->with(1730723729);

        $this->class->set_timestamp(1730723729);
    }

    /**
     * Test that record_timestamp() records the current time for the event.
     *
     * @param Precision $precision Event precision
     * @param int       $expected  Expected timestamp in the precision requested
     *
     * @dataProvider timestampProvider
     * @covers       Lunr\Ticks\InfluxDB1\EventLogging\Event::record_timestamp
     */
    public function testRecordTimestamp(Precision $precision, int $expected): void
    {
        $this->mock_function('time', fn() => 1730723729);
        $this->mock_function('microtime', fn() => 1730723729.1613);
        $this->mock_function('exec', fn() => 1730723729161388499);

        $this->point->expects($this->once())
                    ->method('setTimestamp')
                    ->with($expected);

        $this->class->record_timestamp($precision);

        $this->unmock_function('time');
        $this->unmock_function('microtime');
        $this->unmock_function('exec');
    }

    /**
     * Test that record_timestamp() throws an exception when recording nanoseconds failed.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::record_timestamp
     */
    public function testRecordTimestampFailsWithNanoSecondPrecision(): void
    {
        $this->mock_function('exec', fn() => FALSE);

        $this->point->expects($this->never())
                    ->method('setTimestamp');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not record timestamp with nanosecond precision!');

        $this->class->record_timestamp(Precision::NanoSeconds);

        $this->unmock_function('exec');
    }

}

?>
