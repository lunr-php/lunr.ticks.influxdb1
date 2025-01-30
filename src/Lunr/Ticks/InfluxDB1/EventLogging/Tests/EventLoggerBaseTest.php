<?php

/**
 * This file contains the EventLoggerBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging\Tests;

use Lunr\Halo\PropertyTraits\PsrLoggerTestTrait;

/**
 * This class contains tests for the EventLogger class.
 *
 * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger
 */
class EventLoggerBaseTest extends EventLoggerTestCase
{

    use PsrLoggerTestTrait;

    /**
     * Test that the Client class is passed correctly.
     */
    public function testClientIsPassedCorrectly(): void
    {
        $this->assertPropertySame('client', $this->client);
    }

    /**
     * Test that the default tags are passed correctly.
     */
    public function testDefaultTagsArePassedCorrectly(): void
    {
        $this->assertPropertySame('defaultTags', $this->defaultTags);
    }

    /**
     * Test that the database is initialized as an empty string.
     */
    public function testDatabaseIsEmptyString(): void
    {
        $this->assertPropertySame('database', '');
    }

    /**
     * Test that setDatabase() sets a database name.
     *
     * @covers Lunr\Ticks\InfluxDB1\EventLogging\EventLogger::setDatabase
     */
    public function testSetDatabase(): void
    {
        $this->class->setDatabase('test');

        $this->assertPropertySame('database', 'test');
    }

}

?>
