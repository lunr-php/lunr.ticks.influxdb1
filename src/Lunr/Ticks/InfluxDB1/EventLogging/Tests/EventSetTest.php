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
class EventSetTest extends EventTestCase
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
     * Test that setName() sets the measurement name.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setName
     */
    public function testSetName(): void
    {
        $this->point->expects($this->once())
                    ->method('setMeasurement')
                    ->with('event');

        $this->class->setName('event');
    }

    /**
     * Test that setTraceId() sets the Trace ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setTraceId
     */
    public function testSetTraceId(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'traceID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->setTraceId('4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that setSpanId() sets the Span ID.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setSpanId
     */
    public function testSetSpanId(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'spanID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->setSpanId('4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that setParentSpanId() sets the Span ID of the parent.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setParentSpanId
     */
    public function testSetParentSpanId(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'parentSpanID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->setParentSpanId('4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that setUuidValue() sets a UUID value.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setUuidValue
     */
    public function testSetUuidValue(): void
    {
        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([ 'contentID' => '4e122973-b870-471a-a00e-6a2778244738' ]);

        $this->class->setUuidValue('contentID', '4e122973-b870-471a-a00e-6a2778244738');
    }

    /**
     * Test that setTags() sets the event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setTags
     */
    public function testSetTags(): void
    {
        $tags = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('setTags')
                    ->with($tags);

        $this->class->setTags($tags);
    }

    /**
     * Test that addTags() adds event tags.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::addTags
     */
    public function testAddTags(): void
    {
        $tags = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('addTags')
                    ->with($tags);

        $this->class->addTags($tags);
    }

    /**
     * Test that setFields() sets the event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setFields
     */
    public function testSetFields(): void
    {
        $fields = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('setFields')
                    ->with($fields);

        $this->class->setFields($fields);
    }

    /**
     * Test that addFields() adds event fields.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::addFields
     */
    public function testAddFields(): void
    {
        $fields = [ 'foo' => 'bar' ];

        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with($fields);

        $this->class->addFields($fields);
    }

    /**
     * Test that setTimestamp() sets the event time.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::setTimestamp
     */
    public function testSetTimestamp(): void
    {
        $this->point->expects($this->once())
                    ->method('setTimestamp')
                    ->with(1730723729);

        $this->class->setTimestamp(1730723729);
    }

    /**
     * Test that recordTimestamp() records the current time for the event.
     *
     * @param Precision $precision Event precision
     * @param int       $expected  Expected timestamp in the precision requested
     *
     * @dataProvider timestampProvider
     * @covers       Lunr\Ticks\InfluxDB1\EventLogging\Event::recordTimestamp
     */
    public function testRecordTimestamp(Precision $precision, int $expected): void
    {
        $this->mock_function('time', fn() => 1730723729);
        $this->mock_function('microtime', fn() => 1730723729.1613);
        $this->mock_function('exec', fn() => 1730723729161388499);

        $this->point->expects($this->once())
                    ->method('setTimestamp')
                    ->with($expected);

        $this->class->recordTimestamp($precision);

        $this->unmock_function('time');
        $this->unmock_function('microtime');
        $this->unmock_function('exec');
    }

    /**
     * Test that recordTimestamp() throws an exception when recording nanoseconds failed.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::recordTimestamp
     */
    public function testRecordTimestampFailsWithNanoSecondPrecision(): void
    {
        $this->mock_function('exec', fn() => FALSE);

        $this->point->expects($this->never())
                    ->method('setTimestamp');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not record timestamp with nanosecond precision!');

        $this->class->recordTimestamp(Precision::NanoSeconds);

        $this->unmock_function('exec');
    }

}

?>
