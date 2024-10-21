<?php

/**
 * This file contains the EventBaseTest class.
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
class EventBaseTest extends EventTest
{

    /**
     * Test that the EventLogger class is passed correctly.
     */
    public function testEventLoggerIsPassedCorrectly(): void
    {
        $this->assertPropertySame('eventLogger', $this->eventLogger);
    }

    /**
     * Test that the Point class is passed correctly.
     */
    public function testPointIsPassedCorrectly(): void
    {
        $this->assertPropertySame('point', $this->point);
    }

    /**
     * Test that the database is initialized as an empty string.
     */
    public function testRetentionPolicyIsPassedCorrectly(): void
    {
        $this->assertPropertySame('retentionPolicy', '7d');
    }

}

?>
