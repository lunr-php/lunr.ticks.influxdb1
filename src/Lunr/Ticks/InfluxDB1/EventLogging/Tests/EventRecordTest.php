<?php

/**
 * This file contains the EventRecordTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use Lunr\Ticks\Precision;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event
 */
class EventRecordTest extends EventTestCase
{

    /**
     * Test that record() logs an event with default precision.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::record
     */
    public function testRecordWithDefaultPrecision(): void
    {
        $this->eventLogger->expects($this->once())
                          ->method('record')
                          ->with(
                              $this->point,
                              Precision::NanoSeconds,
                          );

        $this->class->record();
    }

    /**
     * Test that record() logs an event with custom precision.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\Event::record
     */
    public function testRecordWithCustomPrecision(): void
    {
        $this->eventLogger->expects($this->once())
                          ->method('record')
                          ->with(
                              $this->point,
                              Precision::Seconds,
                          );

        $this->class->record(Precision::Seconds);
    }

}

?>
