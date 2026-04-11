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
        $mockDto1 = static::createMockDto('name', 'title');
        $mockDto2 = static::createMockDto('name', 'title');
        $mockDto3 = static::createMockDto('name2', 'title')->setIsProcessed(false);
        $mockDto4 = static::createMockDto('name', 'title2')->setIsProcessed(true);
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
                -1,
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
                0,
            ],
            [
                $mockDto2,
                $mockDto5,
                0,
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
        $mockDto1 = static::createMockDto('name', 'title')->setIsProcessed(true);
        $mockDto2 = static::createMockDto('name', 'title')->setIsProcessed(false);

        return [
            [
                static::createMockDto('name', 'title'),
                static::createMockDto('name', 'title'),
            ],
            [
                static::createMockDto('name', 'title')->setIsProcessed(true),
                static::createMockDto('name2', 'title')->setIsProcessed(true),
            ],
            [
                $mockDto1,
                $mockDto1,
            ],
            [
                $mockDto2,
                static::createMockDto('name', 'title', true)->setIsProcessed(false),
            ],
        ];
    }

    public static function areNotEqualProvider(): array
    {
        return [
            [
                static::createMockDto('name', 'title')->setIsProcessed(true),
                static::createMockDto('name', 'title')->setIsProcessed(false),
            ],
            [
                static::createMockDto('name', 'title')->setIsProcessed(true),
                static::createMockDto('name', 'title'),
            ],
        ];
    }

    public static function generateClosureProvider(): array
    {
        return [
            [
                static::createMockDto('name', 'title')->setIsProcessed(false),
                static::createMockDto('name', 'title2')->setIsProcessed(false),
                static::createMockDto('name2', 'title2')->setIsProcessed(true),
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

    private static function createMockDto(?string $name, ?string $title, bool $internal = false): ProcessableDtoInterface
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
