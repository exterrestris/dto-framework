<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Comparators;

use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Comparators\ComparatorInterface;
use Exterrestris\DtoFramework\Comparators\InterfaceComparator;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerialize;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InterfaceComparator::class)]
class InterfaceComparatorTest extends ComparatorTestCase
{
    public static function compareProvider(): array
    {
        $mockEntity1 = static::createMockEntity('name', 'title');
        $mockEntity2 = static::createMockEntity('name', 'title');
        $mockEntity3 = static::createMockEntity('name2', 'title')->setIsProcessed(false);
        $mockEntity4 = static::createMockEntity('name', 'title2')->setIsProcessed(true);
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
                -1,
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
                0,
            ],
            [
                $mockEntity2,
                $mockEntity5,
                0,
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
        $mockEntity1 = static::createMockEntity('name', 'title')->setIsProcessed(true);
        $mockEntity2 = static::createMockEntity('name', 'title')->setIsProcessed(false);

        return [
            [
                static::createMockEntity('name', 'title'),
                static::createMockEntity('name', 'title'),
            ],
            [
                static::createMockEntity('name', 'title')->setIsProcessed(true),
                static::createMockEntity('name2', 'title')->setIsProcessed(true),
            ],
            [
                $mockEntity1,
                $mockEntity1,
            ],
            [
                $mockEntity2,
                static::createMockEntity('name', 'title', true)->setIsProcessed(false),
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockEntity('name', 'title')->setIsProcessed(true),
                static::createMockEntity('name', 'title')->setIsProcessed(false),
            ],
            [
                static::createMockEntity('name', 'title')->setIsProcessed(true),
                static::createMockEntity('name', 'title'),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        return [
            [
                static::createMockEntity('name', 'title')->setIsProcessed(false),
                static::createMockEntity('name', 'title2')->setIsProcessed(false),
                static::createMockEntity('name2', 'title2')->setIsProcessed(true),
            ],
        ];
    }

    public static function couldMatchProvider(): array
    {
        return [
            [
                ProcessableDtoInterface::class,
                true,
            ],
            [
                DtoInterface::class,
                false,
            ],
        ];
    }

    protected function getComparator(): ComparatorInterface
    {
        $dataExtractor = new DataExtractor();

        return new InterfaceComparator(ProcessableDtoInterface::class, $dataExtractor);
    }

    private static function createMockEntity(?string $name, ?string $title, bool $internal = false): ProcessableDtoInterface
    {
        return new class($name, $title, $internal) implements ProcessableDtoInterface {
            protected ?string $name;
            #[NoSerialize]
            protected ?string $title = null;
            #[Internal]
            protected bool $internal;
            protected string $uninitialized;
            protected ?bool $isProcessed = null;
            protected ?array $processingErrors = null;

            public function __construct(?string $name, ?string $title, bool $internal = false)
            {
                $this->name = $name;
                $this->title = $title;
                $this->internal = $internal;
            }

            public function isProcessed(): ?bool
            {
                return $this->isProcessed;
            }

            /**
             * @noinspection PhpHierarchyChecksInspection Suppress PhpStorm bug ({@link https://youtrack.jetbrains.com/issue/WI-69763})
             */
            public function setIsProcessed(?bool $isProcessed): static
            {
                $this->isProcessed = $isProcessed;

                return $this;
            }

            public function getProcessingErrors(): ?array
            {
                return $this->processingErrors;
            }

            /**
             * @noinspection PhpHierarchyChecksInspection Suppress PhpStorm bug ({@link https://youtrack.jetbrains.com/issue/WI-69763})
             */
            public function setProcessingErrors(?array $processingErrors): static
            {
                $this->processingErrors = $processingErrors;

                return $this;
            }
        };
    }
}
