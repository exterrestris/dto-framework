<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Comparison\Comparator;

use Exterrestris\DtoFramework\Comparison\Comparator\ComparatorInterface;
use Exterrestris\DtoFramework\Comparison\Comparator\IdenticalComparator;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IdenticalComparator::class)]
class IdenticalComparatorTest extends ComparatorTestCase
{
    public static function compareProvider(): array
    {
        $mockDto = static::createMockDto();

        return [
            [
                $mockDto,
                $mockDto,
                0,
            ],
        ];
    }

    public static function areEqualProvider(): array
    {
        $mockDto = static::createMockDto();

        return [
            [
                $mockDto,
                $mockDto,
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockDto(),
                static::createMockDto(),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        $mockDto = static::createMockDto();

        return [
            [
                $mockDto,
                $mockDto,
                static::createMockDto(),
            ],
        ];
    }

    protected function getComparator(): ComparatorInterface
    {
        return new IdenticalComparator();
    }

    private static function createMockDto(): DtoInterface
    {
        return new class() implements DtoInterface {};
    }
}
