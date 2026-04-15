<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use DateTimeImmutable;
use DateTimeInterface;

enum DateRoundingMode
{
    case None;
    case ToStart;
    case ToEnd;

    public function round(
        DateTimeInterface $dateTime,
        DateRoundingUnit $toPrecision = DateRoundingUnit::Day
    ): DateTimeImmutable {
        return match ($this) {
            self::None => DateTimeImmutable::createFromInterface($dateTime),
            self::ToStart => $toPrecision->roundToStart($dateTime),
            self::ToEnd => $toPrecision->roundToEnd($dateTime),
        };
    }
}
