<?php

/**
 * This file contains the ProfilerBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\Profiling\Tests;

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

}

?>
