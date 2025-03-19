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
     * @param EventLogger                                     $eventLogger     An observability event logger
     * @param TracingControllerInterface&TracingInfoInterface $controller      A tracing controller.
     * @param string                                          $name            Event name
     * @param string|null                                     $retentionPolicy Retention policy for the event
     */
    public function __construct(
        EventLogger $eventLogger,
        TracingControllerInterface&TracingInfoInterface $controller,
        string $name,
        ?string $retentionPolicy = NULL,
    )
    {
        parent::__construct($eventLogger->newEvent($name, $retentionPolicy), $controller);
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
