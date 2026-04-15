<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Utility;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Utility\DateRoundingMode;
use Exterrestris\DtoFramework\Utility\DateRoundingUnit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateRoundingMode::class)]
class DateRoundingModeTest extends TestCase
{
    public static function roundProvider(): array
    {
        return [
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::None,
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 00:00:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-01-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:00:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 00:00:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-01 00:00:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-01-01 00:00:00'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-01-01 00:00:00'),
            ],
            [
                new DateTime('2025-04-05 12:20:31'),
                DateRoundingMode::ToStart,
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-30 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 23:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-12-31 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:59'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:59:59'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 23:59:59'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-02-28 23:59:59'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-02-28 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-12-31 23:59:59'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-12-31 23:59:59'),
            ],
            [
                new DateTime('2025-04-05 12:20:31'),
                DateRoundingMode::ToEnd,
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
        ];
    }

    #[DataProvider('roundProvider')]
    public function testRound(
        DateTimeInterface $value,
        DateRoundingMode $roundingMode,
        DateRoundingUnit $precision,
        DateTimeImmutable $expected,
    ): void {
        $this->assertEquals($expected, $roundingMode->round($value, $precision));
    }
}
