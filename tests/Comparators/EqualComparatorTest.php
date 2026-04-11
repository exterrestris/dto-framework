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
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(EqualComparator::class)]
#[UsesClass(DataExtractor::class)]
class EqualComparatorTest extends ComparatorTestCase
{
    public static function compareProvider(): array
    {
        $mockDto1 = static::createMockDto('name', 'title');
        $mockDto2 = static::createMockDto('name', 'title');
        $mockDto3 = static::createMockDto('name2', 'title');
        $mockDto4 = static::createMockDto('name', 'title2');
        $mockDto5 = static::createMockDto('name', null);
        $mockDto6 = static::createMockDto('name', 'title', true);

        return [
            [
                $mockDto1,
                $mockDto1,
                0,
            ],
            [
                $mockDto1,
                $mockDto2,
                0,
            ],
            [
                $mockDto2,
                $mockDto1,
                0,
            ],
            [
                $mockDto3,
                $mockDto2,
                1,
            ],
            [
                $mockDto2,
                $mockDto3,
                -1,
            ],
            [
                $mockDto1,
                $mockDto4,
                -1,
            ],
            [
                $mockDto4,
                $mockDto1,
                1,
            ],
            [
                $mockDto5,
                $mockDto2,
                -1,
            ],
            [
                $mockDto2,
                $mockDto5,
                1,
            ],
            [
                $mockDto1,
                $mockDto6,
                0,
            ],
            [
                $mockDto6,
                $mockDto1,
                0,
            ],
        ];
    }

    public static function areEqualProvider(): array
    {
        $mockDto1 = static::createMockDto('name', 'title');
        $mockDto2 = static::createMockDto('name', 'title');
        $mockDto3 = static::createMockDto('name', 'title', true);

        return [
            [
                $mockDto1,
                $mockDto1,
            ],
            [
                $mockDto1,
                $mockDto2,
            ],
            [
                $mockDto2,
                $mockDto1,
            ],
            [
                $mockDto1,
                $mockDto3,
            ],
            [
                $mockDto3,
                $mockDto1,
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockDto('name', 'title'),
                static::createMockDto('name2', 'title'),
            ],
            [
                static::createMockDto('name', 'title'),
                static::createMockDto('name', null),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        return [
            [
                static::createMockDto('name', 'title'),
                static::createMockDto('name', 'title'),
                static::createMockDto('name', 'title2'),
            ],
            [
                static::createMockDto('name', 'title'),
                static::createMockDto('name', 'title', true),
                static::createMockDto('name', null),
            ],
        ];
    }

    protected function getComparator(): ComparatorInterface
    {
        $dataExtractor = new DataExtractor();

        return new EqualComparator($dataExtractor);
    }

    private static function createMockDto(?string $name, ?string $title, bool $internal = false): DtoInterface
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
