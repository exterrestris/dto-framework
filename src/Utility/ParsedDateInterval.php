<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use DateInterval;
use DateTimeImmutable;

final readonly class ParsedDateInterval {

    /**
     * @param string $intervalString
     * @param DateInterval[] $parsedIntervals
     * @param ?DateTimeImmutable $relativeTo
     * @param DateRoundingMode $roundingMode
     */
    public function __construct(
        private string $intervalString,
        private array $parsedIntervals,
        private ?DateTimeImmutable $relativeTo,
        private DateRoundingMode $roundingMode,
    ) {}

    public function getIntervalString(): string
    {
        return $this->intervalString;
    }

    /**
     * @return DateInterval[]
     */
    public function getParsedIntervals(): array
    {
        return $this->parsedIntervals;
    }

    public function getRelativeTo(): ?DateTimeImmutable
    {
        return $this->relativeTo;
    }

    public function applyToDate(DateTimeImmutable $date): DateTimeImmutable
    {
        foreach ($this->parsedIntervals as $interval) {
            $date = $date->add($interval);
        }

        return DateRoundingUnit::Day->round($date, $this->roundingMode);
    }
}
