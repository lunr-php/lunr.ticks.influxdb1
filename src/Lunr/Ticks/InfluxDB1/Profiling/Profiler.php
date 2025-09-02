<?php

/**
 * This file contains the Profiler class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\Profiling;

use Lunr\Ticks\InfluxDB1\EventLogging\EventLogger;
use Lunr\Ticks\Profiling\Profiler as GenericProfiler;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;

/**
 * A profiler storing to InfluxDB.
 */
class Profiler extends GenericProfiler
{

    /**
     * Constructor.
     *
     * @param EventLogger                                     $eventLogger    An observability event logger
     * @param TracingControllerInterface&TracingInfoInterface $controller     A tracing controller.
     * @param string                                          $name           Event name
     * @param float|null                                      $startTimestamp Custom start timestamp (optional)
     */
    public function __construct(
        EventLogger $eventLogger,
        TracingControllerInterface&TracingInfoInterface $controller,
        string $name,
        ?float $startTimestamp = NULL,
    )
    {
        parent::__construct($eventLogger->newEvent($name), $controller, $startTimestamp);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

}

?>
