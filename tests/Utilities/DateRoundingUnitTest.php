<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Utilities;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Utilities\DateRoundingMode;
use Exterrestris\DtoFramework\Utilities\DateRoundingUnit;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseValueException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateRoundingUnit::class)]
class DateRoundingUnitTest extends TestCase
{
    public static function roundToStartProvider(): array
    {
        return [
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 00:00:00'),
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-01-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:00'),
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:00:00'),
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-05 00:00:00'),
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-04-01 00:00:00'),
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-01 00:00:00'),
            ],
            [
                new DateTimeImmutable('2025-01-01 00:00:00'),
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-01-01 00:00:00'),
            ],
            [
                new DateTime('2025-04-05 12:20:31'),
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
        ];
    }

    #[DataProvider('roundToStartProvider')]
    public function testRoundToStart(
        DateTimeInterface $value,
        DateRoundingUnit $roundingUnit,
        DateTimeImmutable $expected,
    ): void {
        $this->assertEquals($expected, $roundingUnit->roundToStart($value));
    }

    public static function roundToEndProvider(): array
    {
        return [
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:31'),
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-04-30 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 23:20:31'),
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-12-31 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:20:59'),
                DateRoundingUnit::Minute,
                new DateTimeImmutable('2025-04-05 12:20:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 12:59:59'),
                DateRoundingUnit::Hour,
                new DateTimeImmutable('2025-04-05 12:59:59'),
            ],
            [
                new DateTimeImmutable('2025-04-05 23:59:59'),
                DateRoundingUnit::Day,
                new DateTimeImmutable('2025-04-05 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-02-28 23:59:59'),
                DateRoundingUnit::Month,
                new DateTimeImmutable('2025-02-28 23:59:59'),
            ],
            [
                new DateTimeImmutable('2025-12-31 23:59:59'),
                DateRoundingUnit::Year,
                new DateTimeImmutable('2025-12-31 23:59:59'),
            ],
            [
                new DateTime('2025-04-05 12:20:31'),
                DateRoundingUnit::Second,
                new DateTimeImmutable('2025-04-05 12:20:31'),
            ],
        ];
    }

    #[DataProvider('roundToEndProvider')]
    public function testRoundToEnd(
        DateTimeInterface $value,
        DateRoundingUnit $roundingUnit,
        DateTimeImmutable $expected,
    ): void {
        $this->assertEquals($expected, $roundingUnit->roundToEnd($value));
    }

    public static function roundProvider(): array
    {
        $insertDirection = static function (?DateRoundingMode $direction): \Closure {
            return static function(array $testCase) use ($direction): array {
                array_splice($testCase, 2, 0, [$direction]);

                return $testCase;
            };
        };

        return [
            ...array_map($insertDirection(DateRoundingMode::ToStart), self::roundToStartProvider()),
            ...array_map($insertDirection(DateRoundingMode::ToEnd), self::roundToEndProvider()),
        ];
    }

    #[DataProvider('roundProvider')]
    public function testRound(
        DateTimeInterface $value,
        DateRoundingUnit $roundingUnit,
        DateRoundingMode $roundingMode,
        DateTimeImmutable $expected,
    ): void {
        $this->assertEquals($expected, $roundingUnit->round($value, $roundingMode));
    }

    public static function fromFormatProvider(): array
    {
        return [
            [
                'Y-m-d H:i:s',
                DateRoundingUnit::Second,
            ],
            [
                'Y-m-d H:i',
                DateRoundingUnit::Minute,
            ],
            [
                'Y-m-d H',
                DateRoundingUnit::Hour,
            ],
            [
                'Y-m-d',
                DateRoundingUnit::Day,
            ],
            [
                'Y-m',
                DateRoundingUnit::Month,
            ],
            [
                'Y',
                DateRoundingUnit::Year,
            ],
            [
                'd/m/Y',
                DateRoundingUnit::Day,
            ],
            [
                'm/d/Y j:i',
                DateRoundingUnit::Minute,
            ],
            [
                'm/d/y',
                DateRoundingUnit::Day,
            ],
        ];
    }

    #[DataProvider('fromFormatProvider')]
    public function testFromFormat(string $format, DateRoundingUnit $expected): void
    {
        $this->assertEquals($expected, DateRoundingUnit::fromDateFormat($format));
    }

    public static function fromInvalidFormatProvider(): array
    {
        return [
            [
                'Q',
            ],
        ];
    }

    #[DataProvider('fromInvalidFormatProvider')]
    public function testFromInvalidFormat(string $format): void
    {
        $this->expectException(ParseValueException::class);

        DateRoundingUnit::fromDateFormat($format);
    }
}
