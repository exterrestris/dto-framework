<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Serializer\DataParser;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataParserException;
use Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(DataParser::class)]
class DataParserTest extends TestCase
{
    public static function entityDataProvider(): array
    {
        return [
            [
                [
                    'fullName' => 'test',
                    'title' => null,
                    'processed' => false,
                    'processingErrors' => null,
                    'children' => [
                        [
                            'fullName' => 'test',
                            'title' => 'test',
                            'processed' => true,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                        [
                            'fullName' => 'test',
                            'title' => null,
                            'processed' => false,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                    ],
                ]
            ],
            [
                (object) [
                    'fullName' => 'test',
                    'title' => null,
                    'processed' => false,
                    'processingErrors' => null,
                    'children' => [
                        (object) [
                            'fullName' => 'test',
                            'title' => 'test',
                            'processed' => true,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                        (object) [
                            'fullName' => 'test',
                            'title' => null,
                            'processed' => false,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                    ],
                ]
            ],
            [
                [
                    'name' => 'skip',
                    'fullName' => 'test',
                    'title' => null,
                    'processed' => false,
                    'processingErrors' => null,
                    'children' => [
                        [
                            'name' => 'skip',
                            'fullName' => 'test',
                            'title' => 'test',
                            'processed' => true,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                        [
                            'name' => 'skip',
                            'fullName' => 'test',
                            'title' => null,
                            'processed' => false,
                            'processingErrors' => null,
                            'children' => null,
                        ],
                    ],
                ]
            ],
        ];
    }

    #[DataProvider('entityDataProvider')]
    public function testParseIntoFromEntityData($entityData)
    {
        $dataParser = $this->createDataParser();

        $entity = $dataParser->parseInto($entityData, TestEntity::class);

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertEquals('test', $entity->getName());
        $this->assertNull($entity->getTitle());
        $this->assertFalse($entity->isProcessed());
        $this->assertNull($entity->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $entity->getChildren());
        $this->assertEquals(2, $entity->getChildren()->count());

        $this->assertEquals('test', $entity->getChildren()->get(0)->getName());
        $this->assertEquals('test', $entity->getChildren()->get(0)->getTitle());
        $this->assertTrue($entity->getChildren()->get(0)->isProcessed());
        $this->assertNull($entity->getChildren()->get(0)->getProcessingErrors());
        $this->assertNull($entity->getChildren()->get(0)->getChildren());

        $this->assertEquals('test', $entity->getChildren()->get(1)->getName());
        $this->assertNull($entity->getChildren()->get(1)->getTitle());
        $this->assertFalse($entity->getChildren()->get(1)->isProcessed());
        $this->assertNull($entity->getChildren()->get(1)->getProcessingErrors());
        $this->assertNull($entity->getChildren()->get(1)->getChildren());
    }

    public static function entityCollectionDataProvider(): array
    {
        return [
            [
                [
                    [
                        'fullName' => 'test',
                        'title' => null,
                        'processed' => false,
                        'processingErrors' => null,
                        'children' => [
                            [
                                'fullName' => 'test',
                                'title' => 'test',
                                'processed' => true,
                                'processingErrors' => null,
                                'children' => null,
                            ],
                            [
                                'fullName' => 'test',
                                'title' => null,
                                'processed' => false,
                                'processingErrors' => null,
                                'children' => null,
                            ],
                        ],
                    ],
                    [
                        'fullName' => 'test 2',
                        'title' => 'title',
                        'processed' => true,
                        'processingErrors' => null,
                        'children' => [],
                    ],
                ]
            ],
            [
                [
                    (object) [
                        'fullName' => 'test',
                        'title' => null,
                        'processed' => false,
                        'processingErrors' => null,
                        'children' => [
                            (object) [
                                'fullName' => 'test',
                                'title' => 'test',
                                'processed' => true,
                                'processingErrors' => null,
                                'children' => null,
                            ],
                            (object) [
                                'fullName' => 'test',
                                'title' => null,
                                'processed' => false,
                                'processingErrors' => null,
                                'children' => null,
                            ],
                        ],
                    ],
                    (object) [
                        'fullName' => 'test 2',
                        'title' => 'title',
                        'processed' => true,
                        'processingErrors' => null,
                        'children' => [],
                    ],
                ]
            ],
        ];
    }

    #[DataProvider('entityCollectionDataProvider')]
    public function testParseIntoFromEntityCollectionData($collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->parseInto($collectionData, TestEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(TestEntity::class, $collection->getDtoType());
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(TestEntity::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertNull($collection->get(0)->getTitle());
        $this->assertFalse($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(0)->getChildren());
        $this->assertEquals(2, $collection->get(0)->getChildren()->count());

        $this->assertInstanceOf(TestEntity::class, $collection->get(1));
        $this->assertEquals('test 2', $collection->get(1)->getName());
        $this->assertEquals('title', $collection->get(1)->getTitle());
        $this->assertTrue($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(1)->getChildren());
        $this->assertEquals(0, $collection->get(1)->getChildren()->count());
    }

    public function testParseIntoFromInvalidEntityData()
    {
        $entityData = [
            'fullName' => 'test',
            'title' => null,
            'processed' => 'false',
            'processingErrors' => null,
            'children' => 0,
        ];

        $dataParser = $this->createDataParser();

        $this->expectException(DataParserException::class);

        $dataParser->parseInto($entityData, TestEntity::class);
    }

    public static function partiallyInvalidEntityCollectionDataProvider(): array
    {
        return [
            [
                [
                    [
                        'fullName' => 'test',
                        'title' => null,
                        'processed' => 'false',
                        'processingErrors' => null,
                        'children' => 0,
                    ],
                    [
                        'fullName' => 'test 2',
                        'title' => 'title',
                        'processed' => true,
                        'processingErrors' => null,
                        'children' => [],
                    ],
                ],
            ],
            [
                [
                    [
                        'fullName' => 'test 2',
                        'title' => 'title',
                        'processed' => true,
                        'processingErrors' => null,
                        'children' => [],
                    ],
                    [
                        'fullName' => 'test',
                        'title' => null,
                        'processed' => 'false',
                        'processingErrors' => null,
                        'children' => 0,
                    ],
                ],
            ]
        ];
    }

    #[DataProvider('partiallyInvalidEntityCollectionDataProvider')]
    public function testParseIntoFromPartiallyInvalidEntityCollectionData(array $collectionData)
    {
        $dataParser = $this->createDataParser();

        $this->expectException(DataParserException::class);

        $dataParser->parseInto($collectionData, TestEntity::class);
    }

    #[DataProvider('entityDataProvider')]
    public function testTryParseIntoFromEntityData($entityData)
    {
        $dataParser = $this->createDataParser();

        $entity = $dataParser->tryParseInto($entityData, TestEntity::class);

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertEquals('test', $entity->getName());
        $this->assertNull($entity->getTitle());
        $this->assertFalse($entity->isProcessed());
        $this->assertNull($entity->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $entity->getChildren());
        $this->assertEquals(2, $entity->getChildren()->count());

        $this->assertEquals('test', $entity->getChildren()->get(0)->getName());
        $this->assertEquals('test', $entity->getChildren()->get(0)->getTitle());
        $this->assertTrue($entity->getChildren()->get(0)->isProcessed());
        $this->assertNull($entity->getChildren()->get(0)->getProcessingErrors());
        $this->assertNull($entity->getChildren()->get(0)->getChildren());

        $this->assertEquals('test', $entity->getChildren()->get(1)->getName());
        $this->assertNull($entity->getChildren()->get(1)->getTitle());
        $this->assertFalse($entity->getChildren()->get(1)->isProcessed());
        $this->assertNull($entity->getChildren()->get(1)->getProcessingErrors());
        $this->assertNull($entity->getChildren()->get(1)->getChildren());
    }

    #[DataProvider('entityCollectionDataProvider')]
    public function testTryParseIntoFromEntityCollectionData($collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->tryParseInto($collectionData, TestEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(TestEntity::class, $collection->getDtoType());
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(TestEntity::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertNull($collection->get(0)->getTitle());
        $this->assertFalse($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(0)->getChildren());
        $this->assertEquals(2, $collection->get(0)->getChildren()->count());

        $this->assertInstanceOf(TestEntity::class, $collection->get(1));
        $this->assertEquals('test 2', $collection->get(1)->getName());
        $this->assertEquals('title', $collection->get(1)->getTitle());
        $this->assertTrue($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(1)->getChildren());
        $this->assertEquals(0, $collection->get(1)->getChildren()->count());
    }

    public function testTryParseIntoFromInvalidEntityData()
    {
        $entityData = [
            'fullName' => 'test',
            'title' => null,
            'processed' => 'false',
            'processingErrors' => null,
            'children' => 0,
        ];

        $dataParser = $this->createDataParser();

        $entity = $dataParser->tryParseInto($entityData, TestEntity::class);

        $this->assertNull($entity);
    }

    #[DataProvider('partiallyInvalidEntityCollectionDataProvider')]
    public function testTryParseIntoFromPartiallyInvalidEntityCollectionData(array $collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->tryParseInto($collectionData, TestEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(1, $collection->count());

        $this->assertInstanceOf(TestEntity::class, $collection->get(0));
        $this->assertEquals('test 2', $collection->get(0)->getName());
        $this->assertEquals('title', $collection->get(0)->getTitle());
        $this->assertTrue($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(0)->getChildren());
        $this->assertEquals(0, $collection->get(0)->getChildren()->count());
    }

    public function testParseIntoEntityWithCustomPreprocessor()
    {
        $data = [
            'test' => 'title',
        ];

        $dataParser = $this->createDataParser();

        $entity = $dataParser->parseInto($data, CustomSerializationEntity::class);

        $this->assertInstanceOf(CustomSerializationEntity::class, $entity);
        $this->assertEquals('test', $entity->getName());
        $this->assertEquals('title', $entity->getTitle());
        $this->assertNull($entity->isProcessed());
        $this->assertNull($entity->getProcessingErrors());
    }

    public function testParseIntoEntityWithCustomPreprocessorCollection()
    {
        $data = [
            [
                'test' => 'title',
            ],
            [
                'another' => 'long title',
            ],
        ];

        $dataParser = $this->createDataParser();

        $collection = $dataParser->parseInto($data, CustomSerializationEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(CustomSerializationEntity::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertEquals('title', $collection->get(0)->getTitle());
        $this->assertNull($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());

        $this->assertInstanceOf(CustomSerializationEntity::class, $collection->get(1));
        $this->assertEquals('another', $collection->get(1)->getName());
        $this->assertEquals('long title', $collection->get(1)->getTitle());
        $this->assertNull($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
    }

    public function testTryParseIntoEntityWithCustomPreprocessor()
    {
        $data = [
            'test' => 'title',
        ];

        $dataParser = $this->createDataParser();

        $entity = $dataParser->tryParseInto($data, CustomSerializationEntity::class);

        $this->assertInstanceOf(CustomSerializationEntity::class, $entity);
        $this->assertEquals('test', $entity->getName());
        $this->assertEquals('title', $entity->getTitle());
        $this->assertNull($entity->isProcessed());
        $this->assertNull($entity->getProcessingErrors());
    }

    public function testTryParseIntoEntityWithCustomPreprocessorCollection()
    {
        $data = [
            [
                'test' => 'title',
            ],
            [
                'another' => 'long title',
            ],
        ];

        $dataParser = $this->createDataParser();

        $collection = $dataParser->tryParseInto($data, CustomSerializationEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(CustomSerializationEntity::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertEquals('title', $collection->get(0)->getTitle());
        $this->assertNull($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());

        $this->assertInstanceOf(CustomSerializationEntity::class, $collection->get(1));
        $this->assertEquals('another', $collection->get(1)->getName());
        $this->assertEquals('long title', $collection->get(1)->getTitle());
        $this->assertNull($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
    }

    private function createDataParser(): DataParser
    {
        $mockLogger = $this->createMock(LoggerInterface::class);

        return new DataParser(new Factory(), $mockLogger);
    }
}
