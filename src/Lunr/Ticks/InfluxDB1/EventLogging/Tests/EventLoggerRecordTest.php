<?php

/**
 * This file contains the EventLoggerRecordTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use InfluxDB\Database;
use InfluxDB\Exception as InfluxDBException;
use InfluxDB\Point;
use InfluxDB\Precision\InfluxDBV1Precision;
use Lunr\Ticks\Precision;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger
 */
class EventLoggerRecordTest extends EventLoggerTestCase
{

    /**
     * Test that record() logs an event.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::record
     */
    public function testRecordSucceeds(): void
    {
        $this->setReflectionPropertyValue('database', 'test');
        $this->setReflectionPropertyValue('retentionPolicy', '1-month');

        $point = $this->getMockBuilder(Point::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $precision = $this->getMockBuilder(InfluxDBV1Precision::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $database = $this->getMockBuilder(Database::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->client->expects($this->once())
                     ->method('getPrecision')
                     ->willReturn($precision);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->with('test')
                     ->willReturn($database);

        $database->expects($this->once())
                 ->method('writePoints')
                 ->with([ $point ], InfluxDBV1Precision::PRECISION_NANOSECONDS, '1-month');

        $this->class->record($point, Precision::NanoSeconds);
    }

    /**
     * Test that record() logs an event.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::record
     */
    public function testRecordSucceedsWithUnsetRetentionPolicy(): void
    {
        $this->setReflectionPropertyValue('database', 'test');

        $point = $this->getMockBuilder(Point::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $precision = $this->getMockBuilder(InfluxDBV1Precision::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $database = $this->getMockBuilder(Database::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->client->expects($this->once())
                     ->method('getPrecision')
                     ->willReturn($precision);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->with('test')
                     ->willReturn($database);

        $database->expects($this->once())
                 ->method('writePoints')
                 ->with([ $point ], InfluxDBV1Precision::PRECISION_NANOSECONDS, NULL);

        $this->class->record($point, Precision::NanoSeconds);
    }

    /**
     * Test that record() logs a warning when recording to InfluxDB fails.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::record
     */
    public function testRecordFails(): void
    {
        $this->setReflectionPropertyValue('database', 'test');

        $point = $this->getMockBuilder(Point::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $precision = $this->getMockBuilder(InfluxDBV1Precision::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $database = $this->getMockBuilder(Database::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->client->expects($this->once())
                     ->method('getPrecision')
                     ->willReturn($precision);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->with('test')
                     ->willReturn($database);

        $database->expects($this->once())
                 ->method('writePoints')
                 ->willThrowException(new InfluxDBException('InfluxDB Error'));

        $this->logger->expects($this->once())
                     ->method('warning')
                     ->with(
                         'Logging to InfluxDB failed: {error}',
                         [
                             'error' => 'InfluxDB Error',
                         ],
                     );

        $this->class->record($point, Precision::Seconds, '7d');
    }

}

?>
