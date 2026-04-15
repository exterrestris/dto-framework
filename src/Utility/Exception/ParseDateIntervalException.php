<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility\Exception;

/**
 * @internal Must be caught and rethrown
 */
final class ParseDateIntervalException extends ParseValueException
{
    public function __construct(
        protected string $interval,
        ?\Throwable $previous = null
    ) {
        parent::__construct(sprintf('Cannot parse relative date interval "%s"', $this->interval), previous: $previous);
    }

    public function getInterval(): string
    {
        return $this->interval;
    }
}
