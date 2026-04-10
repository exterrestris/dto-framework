<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Comparators;

use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Comparators\ComparatorInterface;
use Exterrestris\DtoFramework\Comparators\EqualComparator;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerialize;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EqualComparator::class)]
class EqualComparatorTest extends ComparatorTestCase
{
    public static function compareProvider(): array
    {
        $mockEntity1 = static::createMockEntity('name', 'title');
        $mockEntity2 = static::createMockEntity('name', 'title');
        $mockEntity3 = static::createMockEntity('name2', 'title');
        $mockEntity4 = static::createMockEntity('name', 'title2');
        $mockEntity5 = static::createMockEntity('name', null);
        $mockEntity6 = static::createMockEntity('name', 'title', true);

        return [
            [
                $mockEntity1,
                $mockEntity1,
                0,
            ],
            [
                $mockEntity1,
                $mockEntity2,
                0,
            ],
            [
                $mockEntity2,
                $mockEntity1,
                0,
            ],
            [
                $mockEntity3,
                $mockEntity2,
                1,
            ],
            [
                $mockEntity2,
                $mockEntity3,
                -1,
            ],
            [
                $mockEntity1,
                $mockEntity4,
                -1,
            ],
            [
                $mockEntity4,
                $mockEntity1,
                1,
            ],
            [
                $mockEntity5,
                $mockEntity2,
                -1,
            ],
            [
                $mockEntity2,
                $mockEntity5,
                1,
            ],
            [
                $mockEntity1,
                $mockEntity6,
                0,
            ],
            [
                $mockEntity6,
                $mockEntity1,
                0,
            ],
        ];
    }

    public static function areEqualProvider(): array
    {
        $mockEntity1 = static::createMockEntity('name', 'title');
        $mockEntity2 = static::createMockEntity('name', 'title');
        $mockEntity3 = static::createMockEntity('name', 'title', true);

        return [
            [
                $mockEntity1,
                $mockEntity1,
            ],
            [
                $mockEntity1,
                $mockEntity2,
            ],
            [
                $mockEntity2,
                $mockEntity1,
            ],
            [
                $mockEntity1,
                $mockEntity3,
            ],
            [
                $mockEntity3,
                $mockEntity1,
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name2', 'title'),
            ],
            [
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name', null),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        return [
            [
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name', 'title2'),
            ],
            [
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name', 'title', true),
                static::createMockEntity('name', null),
            ],
        ];
    }

    protected function getComparator(): ComparatorInterface
    {
        $dataExtractor = new DataExtractor();

        return new EqualComparator($dataExtractor);
    }

    private static function createMockEntity(?string $name, ?string $title, bool $internal = false): DtoInterface
    {
        return new class($name, $title, $internal) implements DtoInterface {
            protected ?string $name;
            #[NoSerialize]
            protected ?string $title = null;
            #[Internal]
            protected bool $internal;
            protected string $uninitialized;

            public function __construct(?string $name, ?string $title, bool $internal = false)
            {
                $this->name = $name;
                $this->title = $title;
                $this->internal = $internal;
            }
        };
    }
}
