<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Comparators;

use Exterrestris\DtoFramework\Comparators\ComparatorInterface;
use Exterrestris\DtoFramework\Comparators\IdenticalComparator;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IdenticalComparator::class)]
class IdenticalComparatorTest extends ComparatorTestCase
{
    public static function compareProvider(): array
    {
        $mockEntity = static::createMockEntity();

        return [
            [
                $mockEntity,
                $mockEntity,
                0,
            ],
        ];
    }

    public static function areEqualProvider(): array
    {
        $mockEntity = static::createMockEntity();

        return [
            [
                $mockEntity,
                $mockEntity,
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockEntity(),
                static::createMockEntity(),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        $mockEntity = static::createMockEntity();

        return [
            [
                $mockEntity,
                $mockEntity,
                static::createMockEntity(),
            ],
        ];
    }

    protected function getComparator(): ComparatorInterface
    {
        return new IdenticalComparator();
    }

    private static function createMockEntity(): DtoInterface
    {
        return new class() implements DtoInterface {};
    }
}
