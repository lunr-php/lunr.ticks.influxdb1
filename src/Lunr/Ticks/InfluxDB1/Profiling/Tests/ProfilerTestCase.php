<?php

/**
 * This file contains the ProfilerTestCase class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\Profiling\Tests;

use Lunr\Halo\LunrBaseTestCase;
use Lunr\Ticks\InfluxDB1\EventLogging\Event;
use Lunr\Ticks\InfluxDB1\EventLogging\EventLogger;
use Lunr\Ticks\InfluxDB1\Profiling\Profiler;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the Profiler class.
 *
 * @covers Lunr\Ticks\InfluxDB1\Profiling\Profiler
 */
abstract class ProfilerTestCase extends LunrBaseTestCase
{

    /**
     * Mock instance of an Event
     * @var Event&MockObject
     */
    protected Event&MockObject $event;

    /**
     * Mock instance of a Tracing Controller
     * @var TracingControllerInterface&TracingInfoInterface
     */
    protected TracingControllerInterface&TracingInfoInterface&MockObject $controller;

    /**
     * Instance of the tested class.
     * @var Profiler
     */
    protected Profiler $class;

    /**
     * Mock value of the start timestamp.
     * @var float
     */
    protected float $startTimestamp = 1734352683.3516;

    /**
     * Testcase Constructor.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->event = $this->getMockBuilder(Event::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $eventlogger = $this->getMockBuilder(EventLogger::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $eventlogger->expects($this->once())
                    ->method('newEvent')
                    ->with('foo')
                    ->willReturn($this->event);

        $this->controller = $this->createMockForIntersectionOfInterfaces(
            [
                TracingControllerInterface::class,
                TracingInfoInterface::class,
            ]
        );

        $floatval  = 1734352683.3516;
        $stringval = '0.35160200 1734352683';

        $this->mockFunction('microtime', fn(bool $float) => $float ? $floatval : $stringval);

        $this->class = new Profiler($eventlogger, $this->controller, 'foo');

        // Unmock here instead of tearDown() because we have another microtime call in the record()
        // method that needs a different mock.
        $this->unmockFunction('microtime');

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        $this->controller->expects($this->once())
                         ->method('getTraceId')
                         ->willReturn('e0af2cd4-6a1c-4bd6-8fca-d3684e699784');

        $this->controller->expects($this->once())
                         ->method('getSpanId')
                         ->willReturn('3f946299-16b5-44ee-8290-3f0fdbbbab1d');

        parent::tearDown();

        unset($this->event);
        unset($this->controller);
        unset($this->class);
    }

}

?>
