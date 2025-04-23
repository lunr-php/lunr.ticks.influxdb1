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
 *
 * @phpstan-import-type Tags from EventInterface
 * @phpstan-import-type Fields from EventInterface
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
     * Constructor.
     *
     * @param EventLogger $eventLogger Instance of the EventLogger that created the Event
     * @param Point       $point       Non-shared InfluxDB1 Point object to hold the event data
     */
    public function __construct(EventLogger $eventLogger, Point $point)
    {
        $this->eventLogger = $eventLogger;
        $this->point       = $point;
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
    public function setName(string $name): void
    {
        $this->point->setMeasurement($name);
    }

    /**
     * Get event name.
     *
     * @return string Event name
     */
    public function getName(): string
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
    public function setTraceId(string $traceID): void
    {
        // InfluxDB 1.x doesn't do well with UUID tag values, so we store this as a field
        $this->point->addFields([ 'traceID' => $traceID ]);
    }

    /**
     * Get trace ID the event belongs to.
     *
     * @return string|null Trace ID
     */
    public function getTraceId(): ?string
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
    public function setSpanId(string $spanID): void
    {
        // InfluxDB 1.x doesn't do well with UUID tag values, so we store this as a field
        $this->point->addFields([ 'spanID' => $spanID ]);
    }

    /**
     * Get span ID the event belongs to.
     *
     * @return string|null Span ID
     */
    public function getSpanId(): ?string
    {
        return $this->point->getFields()['spanID'] ?? NULL;
    }

    /**
     * Set span ID of the parent the event belongs to.
     *
     * @param string $spanID Span ID
     *
     * @return void
     */
    public function setParentSpanId(string $spanID): void
    {
        $this->point->addFields([ 'parentSpanID' => $spanID ]);
    }

    /**
     * Get span ID of the parent the event belongs to.
     *
     * @return string|null Parent span ID
     */
    public function getParentSpanId(): ?string
    {
        return $this->point->getFields()['parentSpanID'] ?? NULL;
    }

    /**
     * Set a UUID value.
     *
     * @param string $key  Name for the UUID value
     * @param string $uuid The UUID to set
     *
     * @return void
     */
    public function setUuidValue(string $key, string $uuid): void
    {
        $this->point->addFields([ $key => $uuid ]);
    }

    /**
     * Set indexed metadata.
     *
     * This clears all previously set values and replaces them.
     *
     * @param Tags $tags Indexed metadata
     *
     * @return void
     */
    public function setTags(array $tags): void
    {
        $this->point->setTags($tags);
    }

    /**
     * Add indexed metadata.
     *
     * Set new values on top of previously set values.
     *
     * @param Tags $tags Indexed metadata
     *
     * @return void
     */
    public function addTags(array $tags): void
    {
        $this->point->addTags($tags);
    }

    /**
     * Get indexed metadata.
     *
     * influxdb-php will filter out NULL values, so those would never be returned.
     *
     * @return array<string,string> Indexed metadata
     */
    public function getTags(): array
    {
        return $this->point->getTags();
    }

    /**
     * Set unstructured metadata.
     *
     * This clears all previously set values and replaces them.
     *
     * @param Fields $fields Unstructured metadata
     *
     * @return void
     */
    public function setFields(array $fields): void
    {
        $this->point->setFields($fields);
    }

    /**
     * Add unstructured metadata.
     *
     * Set new values on top of previously set values.
     *
     * @param Fields $fields Unstructured metadata
     *
     * @return void
     */
    public function addFields(array $fields): void
    {
        $this->point->addFields($fields);
    }

    /**
     * Get unstructured metadata.
     *
     * influxdb-php will filter out NULL values, so those would never be returned.
     *
     * @return array<string,scalar> Unstructured metadata
     */
    public function getFields(): array
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
    public function recordTimestamp(Precision $precision = Precision::NanoSeconds): void
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
    public function setTimestamp(int|string $timestamp): void
    {
        $this->point->setTimestamp((string) $timestamp);
    }

    /**
     * Return the timestamp for the event.
     *
     * @return int|string Timestamp
     */
    public function getTimestamp(): int|string
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
    public function record(Precision $precision = Precision::NanoSeconds): void
    {
        $this->eventLogger->record($this->point, $precision);
    }

}

?>
