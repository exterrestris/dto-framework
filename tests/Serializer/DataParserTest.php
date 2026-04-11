<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Serializer;

use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Collection\AbstractCollection;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Factory\AbstractFactory;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Serializer\Config\UseDataParserPreprocessor;
use Exterrestris\DtoFramework\Serializer\DataParser;
use Exterrestris\DtoFramework\Serializer\Exceptions\AbstractDataParserException;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataParserException;
use Exterrestris\DtoFramework\Serializer\Rules\Map;
use Exterrestris\DtoFramework\Serializer\Rules\MapFrom;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(DataParser::class)]
#[UsesClass(AbstractDto::class)]
#[UsesClass(CollectionType::class)]
#[UsesClass(AbstractCollection::class)]
#[UsesClass(Collection::class)]
#[UsesClass(AbstractFactory::class)]
#[UsesClass(Factory::class)]
#[UsesClass(Map::class)]
#[UsesClass(MapFrom::class)]
#[UsesClass(AbstractDataParserException::class)]
#[UsesClass(UseDataParserPreprocessor::class)]
class DataParserTest extends TestCase
{
    public static function dtoDataProvider(): array
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

    #[DataProvider('dtoDataProvider')]
    public function testParseIntoFromEntityData($dtoData)
    {
        $dataParser = $this->createDataParser();

        $dto = $dataParser->parseInto($dtoData, MockDto::class);

        $this->assertInstanceOf(MockDto::class, $dto);
        $this->assertEquals('test', $dto->getName());
        $this->assertNull($dto->getTitle());
        $this->assertFalse($dto->isProcessed());
        $this->assertNull($dto->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $dto->getChildren());
        $this->assertEquals(2, $dto->getChildren()->count());

        $this->assertEquals('test', $dto->getChildren()->get(0)->getName());
        $this->assertEquals('test', $dto->getChildren()->get(0)->getTitle());
        $this->assertTrue($dto->getChildren()->get(0)->isProcessed());
        $this->assertNull($dto->getChildren()->get(0)->getProcessingErrors());
        $this->assertNull($dto->getChildren()->get(0)->getChildren());

        $this->assertEquals('test', $dto->getChildren()->get(1)->getName());
        $this->assertNull($dto->getChildren()->get(1)->getTitle());
        $this->assertFalse($dto->getChildren()->get(1)->isProcessed());
        $this->assertNull($dto->getChildren()->get(1)->getProcessingErrors());
        $this->assertNull($dto->getChildren()->get(1)->getChildren());
    }

    public static function collectionDataProvider(): array
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

    #[DataProvider('collectionDataProvider')]
    public function testParseIntoFromEntityCollectionData($collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->parseInto($collectionData, MockDto::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(MockDto::class, $collection->getDtoType());
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(MockDto::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertNull($collection->get(0)->getTitle());
        $this->assertFalse($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(0)->getChildren());
        $this->assertEquals(2, $collection->get(0)->getChildren()->count());

        $this->assertInstanceOf(MockDto::class, $collection->get(1));
        $this->assertEquals('test 2', $collection->get(1)->getName());
        $this->assertEquals('title', $collection->get(1)->getTitle());
        $this->assertTrue($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(1)->getChildren());
        $this->assertEquals(0, $collection->get(1)->getChildren()->count());
    }

    public function testParseIntoFromInvalidEntityData()
    {
        $dtoData = [
            'fullName' => 'test',
            'title' => null,
            'processed' => 'false',
            'processingErrors' => null,
            'children' => 0,
        ];

        $dataParser = $this->createDataParser();

        $this->expectException(DataParserException::class);

        $dataParser->parseInto($dtoData, MockDto::class);
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

        $dataParser->parseInto($collectionData, MockDto::class);
    }

    #[DataProvider('dtoDataProvider')]
    public function testTryParseIntoFromEntityData($dtoData)
    {
        $dataParser = $this->createDataParser();

        $dto = $dataParser->tryParseInto($dtoData, MockDto::class);

        $this->assertInstanceOf(MockDto::class, $dto);
        $this->assertEquals('test', $dto->getName());
        $this->assertNull($dto->getTitle());
        $this->assertFalse($dto->isProcessed());
        $this->assertNull($dto->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $dto->getChildren());
        $this->assertEquals(2, $dto->getChildren()->count());

        $this->assertEquals('test', $dto->getChildren()->get(0)->getName());
        $this->assertEquals('test', $dto->getChildren()->get(0)->getTitle());
        $this->assertTrue($dto->getChildren()->get(0)->isProcessed());
        $this->assertNull($dto->getChildren()->get(0)->getProcessingErrors());
        $this->assertNull($dto->getChildren()->get(0)->getChildren());

        $this->assertEquals('test', $dto->getChildren()->get(1)->getName());
        $this->assertNull($dto->getChildren()->get(1)->getTitle());
        $this->assertFalse($dto->getChildren()->get(1)->isProcessed());
        $this->assertNull($dto->getChildren()->get(1)->getProcessingErrors());
        $this->assertNull($dto->getChildren()->get(1)->getChildren());
    }

    #[DataProvider('collectionDataProvider')]
    public function testTryParseIntoFromEntityCollectionData($collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->tryParseInto($collectionData, MockDto::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(MockDto::class, $collection->getDtoType());
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(MockDto::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertNull($collection->get(0)->getTitle());
        $this->assertFalse($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(0)->getChildren());
        $this->assertEquals(2, $collection->get(0)->getChildren()->count());

        $this->assertInstanceOf(MockDto::class, $collection->get(1));
        $this->assertEquals('test 2', $collection->get(1)->getName());
        $this->assertEquals('title', $collection->get(1)->getTitle());
        $this->assertTrue($collection->get(1)->isProcessed());
        $this->assertNull($collection->get(1)->getProcessingErrors());
        $this->assertInstanceOf(Collection::class, $collection->get(1)->getChildren());
        $this->assertEquals(0, $collection->get(1)->getChildren()->count());
    }

    public function testTryParseIntoFromInvalidEntityData()
    {
        $dtoData = [
            'fullName' => 'test',
            'title' => null,
            'processed' => 'false',
            'processingErrors' => null,
            'children' => 0,
        ];

        $dataParser = $this->createDataParser();

        $dto = $dataParser->tryParseInto($dtoData, MockDto::class);

        $this->assertNull($dto);
    }

    #[DataProvider('partiallyInvalidEntityCollectionDataProvider')]
    public function testTryParseIntoFromPartiallyInvalidEntityCollectionData(array $collectionData)
    {
        $dataParser = $this->createDataParser();

        $collection = $dataParser->tryParseInto($collectionData, MockDto::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(1, $collection->count());

        $this->assertInstanceOf(MockDto::class, $collection->get(0));
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

        $dto = $dataParser->parseInto($data, MockCustomSerializationDto::class);

        $this->assertInstanceOf(MockCustomSerializationDto::class, $dto);
        $this->assertEquals('test', $dto->getName());
        $this->assertEquals('title', $dto->getTitle());
        $this->assertNull($dto->isProcessed());
        $this->assertNull($dto->getProcessingErrors());
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

        $collection = $dataParser->parseInto($data, MockCustomSerializationDto::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(MockCustomSerializationDto::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertEquals('title', $collection->get(0)->getTitle());
        $this->assertNull($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());

        $this->assertInstanceOf(MockCustomSerializationDto::class, $collection->get(1));
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

        $dto = $dataParser->tryParseInto($data, MockCustomSerializationDto::class);

        $this->assertInstanceOf(MockCustomSerializationDto::class, $dto);
        $this->assertEquals('test', $dto->getName());
        $this->assertEquals('title', $dto->getTitle());
        $this->assertNull($dto->isProcessed());
        $this->assertNull($dto->getProcessingErrors());
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

        $collection = $dataParser->tryParseInto($data, MockCustomSerializationDto::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(2, $collection->count());

        $this->assertInstanceOf(MockCustomSerializationDto::class, $collection->get(0));
        $this->assertEquals('test', $collection->get(0)->getName());
        $this->assertEquals('title', $collection->get(0)->getTitle());
        $this->assertNull($collection->get(0)->isProcessed());
        $this->assertNull($collection->get(0)->getProcessingErrors());

        $this->assertInstanceOf(MockCustomSerializationDto::class, $collection->get(1));
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
