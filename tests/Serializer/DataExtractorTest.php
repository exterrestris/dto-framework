<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntityWithCustomSerialization;
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
                    'name' => 'test',
                    'title' => 'test',
                    'uninitialized' => '',
                    'isProcessed' => true,
                    'processingErrors' => null,
                    'children' => null,
                ],
            ],
            [
                static::createMockEntity('test', null, true)->setIsProcessed(false),
                [
                    'name' => 'test',
                    'title' => null,
                    'uninitialized' => '',
                    'isProcessed' => false,
                    'processingErrors' => null,
                    'children' => null,
                ],
            ],
            [
                static::createMockEntity('test', null)
                    ->setIsProcessed(false)
                    ->setChildren(new Collection(ProcessableDtoInterface::class)),
                [
                    'name' => 'test',
                    'title' => null,
                    'uninitialized' => '',
                    'isProcessed' => false,
                    'processingErrors' => null,
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
                    'name' => 'test',
                    'title' => null,
                    'uninitialized' => '',
                    'isProcessed' => false,
                    'processingErrors' => null,
                    'children' => [
                        [
                            'name' => 'test',
                            'title' => 'test',
                            'uninitialized' => '',
                            'isProcessed' => true,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                        [
                            'name' => 'test',
                            'title' => null,
                            'uninitialized' => '',
                            'isProcessed' => false,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('getDataFromEntityProvider')]
    public function testGetDataFromEntity(DtoInterface $entity, array $expected)
    {
        $dataExtractor = new DataExtractor();

        $data = $dataExtractor->getData($entity);

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
                'name' => 'test',
                'title' => 'test',
                'uninitialized' => '',
                'isProcessed' => true,
                'processingErrors' => null,
                'children' => null,
            ],
            [
                'name' => 'test-2',
                'title' => null,
                'uninitialized' => '',
                'isProcessed' => false,
                'processingErrors' => null,
                'children' => null,
            ],
        ], $data);
    }

    private static function createMockEntity(?string $name, ?string $title, bool $internal = false): TestEntity
    {
        return (new TestEntity())->setName($name)->setTitle($title)->setInternal($internal);
    }
}
