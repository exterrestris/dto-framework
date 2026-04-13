<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Serializer;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\Collection\AbstractCollection;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\Config\OverrideDataExtractor;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Serializer\Rules\Map;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockNamedDtoInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DataExtractor::class)]
#[UsesClass(AbstractDto::class)]
#[UsesClass(AbstractCollection::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Map::class)]
#[UsesClass(OverrideDataExtractor::class)]
class DataExtractorTest extends TestCase
{

    public static function getDataFromDtoProvider(): array
    {
        return [
            [
                (new MockDto())
                    ->setName('test')
                    ->setTitle('test')
                    ->setInternal(true)
                    ->setIsProcessed(true)
                    ->setDate(new DateTimeImmutable('2026-01-01')),
                [
                    'fullName' => 'test',
                    'title' => 'test',
                    'uninitialized' => '',
                    'date' => '01/01/2026',
                ],
            ],
            [
                (new MockDto())
                    ->setName('test')
                    ->setTitle(null)
                    ->setInternal(true)
                    ->setIsProcessed(false),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                    'date' => null,
                ],
            ],
            [
                (new MockDto())
                    ->setName('test')
                    ->setTitle(null)
                    ->setInternal(false)
                    ->setIsProcessed(false)
                    ->setChildren(new Collection(MockNamedDtoInterface::class)),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                    'date' => null,
                    'children' => [],
                ],
            ],
            [
                (new MockDto())
                    ->setName('test')
                    ->setTitle(null)
                    ->setInternal(false)
                    ->setIsProcessed(false)
                    ->setChildren(new Collection(MockNamedDtoInterface::class, [
                        (new MockDto())
                            ->setName('test')
                            ->setTitle('test')
                            ->setInternal(true)
                            ->setIsProcessed(true),
                        (new MockDto())
                            ->setName('test')
                            ->setTitle(null)
                            ->setInternal(false)
                            ->setIsProcessed(false),
                    ])),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                    'date' => null,
                    'children' => [
                        [
                            'fullName' => 'test',
                            'title' => 'test',
                            'uninitialized' => '',
                            'date' => null,
                        ],
                        [
                            'fullName' => 'test',
                            'uninitialized' => '',
                            'date' => null,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('getDataFromDtoProvider')]
    public function testGetDataFromDto(DtoInterface $dto, array $expected): void
    {
        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($dto);

        $this->assertIsArray($data);
        $this->assertSame($expected, $data);
    }

    public function testGetDataFromCollection(): void
    {
        $collection = (new Collection(MockNamedDtoInterface::class))->add(
            (new MockDto())
                ->setName('test')
                ->setTitle('test')
                ->setInternal(true)
                ->setIsProcessed(true),
            (new MockDto())
                ->setName('test-2')
                ->setTitle(null)
                ->setInternal(true)
                ->setIsProcessed(false),
        );

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($collection);

        $this->assertIsArray($data);
        $this->assertSame([
            [
                'fullName' => 'test',
                'title' => 'test',
                'uninitialized' => '',
                'date' => null,
            ],
            [
                'fullName' => 'test-2',
                'uninitialized' => '',
                'date' => null,
            ],
        ], $data);
    }

    public function testGetDataWithCustomExtractor(): void
    {
        $dto = (new MockCustomSerializationDto())->setName('test')->setTitle('Test Entity');

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($dto);

        $this->assertIsArray($data);
        $this->assertSame([
            'test' => 'Test Entity',
        ], $data);
    }

    public function testGetDataFromCollectionWithCustomExtractor(): void
    {
        $collection = (new Collection(MockCustomSerializationDto::class))->add(
            (new MockCustomSerializationDto())->setName('test-1')->setTitle('Test Entity 1'),
            (new MockCustomSerializationDto())->setName('test-2')->setTitle('Test Entity 2'),
        );

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($collection);

        $this->assertIsArray($data);
        $this->assertSame([
            ['test-1' => 'Test Entity 1'],
            ['test-2' => 'Test Entity 2'],
        ], $data);
    }
}
