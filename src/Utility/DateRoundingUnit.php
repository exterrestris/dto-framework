<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Utility\Exception\ParseValueException;

enum DateRoundingUnit
{
    case Year;
    case Month;
    case Day;
    case Hour;
    case Minute;
    case Second;

    public function round(
        DateTimeInterface $dateTime,
        DateRoundingMode $mode = DateRoundingMode::ToStart
    ): DateTimeImmutable {
        return $mode->round($dateTime, $this);
    }

    public function roundToStart(DateTimeInterface $dateTime): DateTimeImmutable
    {
        $dateTime = $this->normalise($dateTime);

        return match ($this) {
            self::Year => $dateTime
                ->setDate((int) $dateTime->format('Y'), 1, 1)
                ->setTime(0, 0, 0),
            self::Month => $dateTime
                ->setDate((int) $dateTime->format('Y'), (int) $dateTime->format('m'), 1)
                ->setTime(0, 0, 0),
            self::Day => $dateTime->setTime(0, 0, 0),
            self::Hour => $dateTime->setTime((int) $dateTime->format('H'), 0, 0),
            self::Minute => $dateTime->setTime((int) $dateTime->format('H'), (int) $dateTime->format('i'), 0),
            self::Second => $dateTime,
        };
    }

    public function roundToEnd(DateTimeInterface $dateTime): DateTimeImmutable
    {
        $dateTime = $this->normalise($dateTime);

        return match ($this) {
            self::Year => $dateTime
                ->setDate((int) $dateTime->format('Y') + 1, 1, 0)
                ->setTime(23, 59, 59),
            self::Month => $dateTime
                ->setDate((int) $dateTime->format('Y'), (int) $dateTime->format('m') + 1, 0)
                ->setTime(23, 59, 59),
            self::Day => $dateTime
                ->setDate((int) $dateTime->format('Y'), (int) $dateTime->format('m'), (int) $dateTime->format('d') + 1)
                ->setTime(0, 0, -1),
            self::Hour => $dateTime->setTime((int) $dateTime->format('G'), 59, 59),
            self::Minute => $dateTime->setTime((int) $dateTime->format('G'), (int) $dateTime->format('i'), 59),
            self::Second => $dateTime,
        };
    }

    private function normalise(DateTimeInterface $dateTime): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($dateTime);
    }

    public static function fromDateFormat(string $format): self
    {
        if (str_contains($format, 's')) {
            return self::Second;
        }

        if (str_contains($format, 'i')) {
            return self::Minute;
        }

        if (preg_match('/[ghGH]/', $format)) {
            return self::Hour;
        }

        if (preg_match('/[djDl]/', $format)) {
            return self::Day;
        }

        if (preg_match('/[nmFM]/', $format)) {
            return self::Month;
        }

        if (preg_match('/[yYXx]/', $format)) {
            return self::Year;
        }

        throw new ParseValueException(sprintf('Cannot determine date precision from format "%s"', $format));
    }
}
