<?php

/**
 * This file contains the Event class.
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\InfluxDB1\EventLogging;

use InfluxDB\Point;
use Lunr\Ticks\EventLogging\EventInterface;
use Lunr\Ticks\Precision;
use RuntimeException;

/**
 * Class for events.
 */
class Event implements EventInterface
{

    /**
     * Instance of of the InfluxDB event logger
     * @var EventLogger
     */
    protected readonly EventLogger $eventLogger;

    /**
     * Instance of the Point class.
     * @var Point
     */
    protected readonly Point $point;

    /**
     * Retention policy for the event.
     * @var string|null
     */
    protected readonly ?string $retentionPolicy;

    /**
     * Constructor.
     *
     * @param EventLogger $eventLogger     Instance of the EventLogger that created the Event
     * @param Point       $point           Non-shared InfluxDB1 Point object to hold the event data
     * @param string|null $retentionPolicy Retention policy for the event
     */
    public function __construct(EventLogger $eventLogger, Point $point, ?string $retentionPolicy)
    {
        $this->eventLogger     = $eventLogger;
        $this->point           = $point;
        $this->retentionPolicy = $retentionPolicy;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // no-op
    }

    /**
     * Set event name.
     *
     * @param string $name Event name
     *
     * @return void
     */
    public function set_name(string $name): void
    {
        $this->point->setMeasurement($name);
    }

    /**
     * Get event name.
     *
     * @return string Event name
     */
    public function get_name(): string
    {
        return $this->point->getMeasurement();
    }

    /**
     * Set trace ID the event belongs to.
     *
     * @param string $traceID Trace ID
     *
     * @return void
     */
    public function set_trace_id(string $traceID): void
    {
        // InfluxDB 1.x doesn't do well with UUID tag values, so we store this as a field
        $this->point->addFields([ 'traceID' => $traceID ]);
    }

    /**
     * Get trace ID the event belongs to.
     *
     * @return string|null Trace ID
     */
    public function get_trace_id(): ?string
    {
        return $this->point->getFields()['traceID'] ?? NULL;
    }

    /**
     * Set span ID the event belongs to.
     *
     * @param string $spanID Span ID
     *
     * @return void
     */
    public function set_span_id(string $spanID): void
    {
        // InfluxDB 1.x doesn't do well with UUID tag values, so we store this as a field
        $this->point->addFields([ 'spanID' => $spanID ]);
    }

    /**
     * Get span ID the event belongs to.
     *
     * @return string|null Span ID
     */
    public function get_span_id(): ?string
    {
        return $this->point->getFields()['spanID'] ?? NULL;
    }

    /**
     * Set indexed metadata.
     *
     * This clears all previously set values and replaces them.
     *
     * @param array<string,string|null> $tags Indexed metadata
     *
     * @return void
     */
    public function set_tags(array $tags): void
    {
        $this->point->setTags($tags);
    }

    /**
     * Add indexed metadata.
     *
     * Set new values on top of previously set values.
     *
     * @param array<string,string|null> $tags Indexed metadata
     *
     * @return void
     */
    public function add_tags(array $tags): void
    {
        $this->point->addTags($tags);
    }

    /**
     * Get indexed metadata.
     *
     * @return array<string,string> Indexed metadata
     */
    public function get_tags(): array
    {
        return $this->point->getTags();
    }

    /**
     * Set unstructured metadata.
     *
     * This clears all previously set values and replaces them.
     *
     * @param array<string,bool|float|int|string|null> $fields Unstructured metadata
     *
     * @return void
     */
    public function set_fields(array $fields): void
    {
        $this->point->setFields($fields);
    }

    /**
     * Add unstructured metadata.
     *
     * Set new values on top of previously set values.
     *
     * @param array<string,bool|float|int|string|null> $fields Unstructured metadata
     *
     * @return void
     */
    public function add_fields(array $fields): void
    {
        $this->point->addFields($fields);
    }

    /**
     * Get unstructured metadata.
     *
     * @return array<string,bool|float|int|string> Unstructured metadata
     */
    public function get_fields(): array
    {
        return $this->point->getFields();
    }

    /**
     * Record the current timestamp for the event.
     *
     * @param Precision $precision Timestamp precision (defaults to Nanoseconds)
     *
     * @return void
     */
    public function record_timestamp(Precision $precision = Precision::NanoSeconds): void
    {
        switch ($precision)
        {
            case Precision::Hours:
                $timestamp = (string) ((int) round(time() / 3600));
                break;
            case Precision::Minutes:
                $timestamp = (string) ((int) round(time() / 60));
                break;
            case Precision::Seconds:
                $timestamp = (string) time();
                break;
            case Precision::MilliSeconds:
                $timestamp = sprintf('%d', (int) round(microtime(TRUE) * 1000));
                break;
            case Precision::MicroSeconds:
                $timestamp = sprintf('%d', microtime(TRUE) * 1000000);
                break;
            case Precision::NanoSeconds:
            default:
                $timestamp = exec('date +%s%N');

                if ($timestamp === FALSE)
                {
                    throw new RuntimeException('Could not record timestamp with nanosecond precision!');
                }

                break;
        }

        $this->point->setTimestamp($timestamp);
    }

    /**
     * Set custom timestamp for the event.
     *
     * @param int|string $timestamp Timestamp
     *
     * @return void
     */
    public function set_timestamp(int|string $timestamp): void
    {
        $this->point->setTimestamp((string) $timestamp);
    }

    /**
     * Return the timestamp for the event.
     *
     * @return int|string Timestamp
     */
    public function get_timestamp(): int|string
    {
        return $this->point->getTimestamp();
    }

    /**
     * Record the event.
     *
     * @param Precision $precision Timestamp precision (defaults to Nanoseconds)
     *
     * @return void
     */
    public function record($precision = Precision::NanoSeconds): void
    {
        $this->eventLogger->record($this->point, $precision, $this->retentionPolicy);
    }

}

?>
