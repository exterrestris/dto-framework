<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DataExtractor::class)]
class DataExtractorTest extends TestCase
{

    public static function getDataFromEntityProvider(): array
    {
        return [
            [
                static::createMockEntity('test', 'test', true)->setIsProcessed(true),
                [
                    'fullName' => 'test',
                    'title' => 'test',
                    'uninitialized' => '',
                ],
            ],
            [
                static::createMockEntity('test', null, true)->setIsProcessed(false),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                ],
            ],
            [
                static::createMockEntity('test', null)
                    ->setIsProcessed(false)
                    ->setChildren(new Collection(ProcessableDtoInterface::class)),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                    'children' => [],
                ],
            ],
            [
                static::createMockEntity('test', null)
                    ->setIsProcessed(false)
                    ->setChildren(new Collection(ProcessableDtoInterface::class, [
                        static::createMockEntity('test', 'test', true)->setIsProcessed(true),
                        static::createMockEntity('test', null)->setIsProcessed(false),
                    ])),
                [
                    'fullName' => 'test',
                    'uninitialized' => '',
                    'children' => [
                        [
                            'fullName' => 'test',
                            'title' => 'test',
                            'uninitialized' => '',
                        ],
                        [
                            'fullName' => 'test',
                            'uninitialized' => '',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('getDataFromEntityProvider')]
    public function testGetDataFromEntity(DtoInterface $dto, array $expected)
    {
        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($dto);

        $this->assertIsArray($data);
        $this->assertSame($expected, $data);
    }

    public function testGetDataFromCollection()
    {
        $collection = (new Collection(ProcessableDtoInterface::class))->add(
            static::createMockEntity('test', 'test', true)->setIsProcessed(true),
            static::createMockEntity('test-2', null, true)->setIsProcessed(false),
        );

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($collection);

        $this->assertIsArray($data);
        $this->assertSame([
            [
                'fullName' => 'test',
                'title' => 'test',
                'uninitialized' => '',
            ],
            [
                'fullName' => 'test-2',
                'uninitialized' => '',
            ],
        ], $data);
    }

    public function testGetDataWithCustomExtractor()
    {
        $dto = $this->createMockEntityWithDataExtractor('test', 'Test Entity');

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($dto);

        $this->assertIsArray($data);
        $this->assertSame([
            'test' => 'Test Entity',
        ], $data);
    }

    public function testGetDataFromCollectionWithCustomExtractor()
    {
        $collection = (new Collection(MockCustomSerializationDto::class))->add(
            $this->createMockEntityWithDataExtractor('test-1', 'Test Entity 1'),
            $this->createMockEntityWithDataExtractor('test-2', 'Test Entity 2'),
        );

        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($collection);

        $this->assertIsArray($data);
        $this->assertSame([
            ['test-1' => 'Test Entity 1'],
            ['test-2' => 'Test Entity 2'],
        ], $data);
    }

    private static function createMockEntity(?string $name, ?string $title, bool $internal = false): MockDto
    {
        return (new MockDto())->setName($name)->setTitle($title)->setInternal($internal);
    }

    private function createMockEntityWithDataExtractor(?string $name, ?string $title): MockCustomSerializationDto
    {
        return (new MockCustomSerializationDto())->setName($name)->setTitle($title);
    }
}
