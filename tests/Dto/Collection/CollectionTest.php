<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Collection;

use Exterrestris\DtoFramework\Comparators\EquivalentComparator;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\AlreadyInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\NotInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\AbstractProcessableDto;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockBasicDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Collection::class)]
class CollectionTest extends TestCase
{
    public static function constructWithInvalidTypeProvider(): array
    {
        return [
            [
                DtoInterface::class,
            ],
            [
                CollectionInterface::class,
            ],
        ];
    }

    #[DataProvider('constructWithInvalidTypeProvider')]
    public function testConstructWithInvalidType(string $dtoType)
    {
        $this->expectException(InvalidTypeException::class);

        new Collection($dtoType);
    }

    public function testConstructWithValidItems()
    {
        $collection = new Collection(MockDto::class, [new MockDto()]);

        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function testConstructWithInvalidItems()
    {
        $this->expectException(IncompatibleDtoException::class);

        new Collection(MockDto::class, [new MockBasicDto()]);
    }

    public function testIsOfType()
    {
        $specificCollection = new Collection(MockDto::class);
        $typeCollection = new Collection(MockDtoInterface::class);
        $genericCollection = new Collection(ProcessableDtoInterface::class);

        $this->assertTrue($specificCollection->isOfType(MockDto::class));
        $this->assertTrue($specificCollection->isOfType(MockDtoInterface::class));
        $this->assertTrue($specificCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($specificCollection->isOfType(ProcessableDtoInterface::class));

        $this->assertFalse($typeCollection->isOfType(MockDto::class));
        $this->assertTrue($typeCollection->isOfType(MockDtoInterface::class));
        $this->assertFalse($typeCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($typeCollection->isOfType(ProcessableDtoInterface::class));

        $this->assertFalse($genericCollection->isOfType(MockDto::class));
        $this->assertFalse($genericCollection->isOfType(MockDtoInterface::class));
        $this->assertFalse($genericCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($genericCollection->isOfType(ProcessableDtoInterface::class));
    }

    public function testGetEntityType()
    {
        $collection = new Collection(MockDto::class);

        $this->assertEquals(MockDto::class, $collection->getDtoType());
    }

    public function testContains()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);

        $this->assertTrue($collection->contains($dto));
        $this->assertFalse($collection->contains(new MockDto()));
    }

    public function testClear()
    {
        $collection = new Collection(MockDto::class, [new MockDto()]);
        $cleared = $collection->clear();

        $this->assertInstanceOf(Collection::class, $cleared);
        $this->assertNotSame($collection, $cleared);
        $this->assertEquals(0, $cleared->count());
        $this->assertEmpty($cleared->toArray());
    }

    public function testFirst()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);
        $emptyCollection = new Collection(MockDto::class);

        $this->assertSame($dto, $collection->first());
        $this->assertNull($emptyCollection->first());
    }

    public function testIsEmpty()
    {
        $emptyCollection = new Collection(MockDto::class);
        $collection = new Collection(MockDto::class, [new MockDto()]);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertFalse($collection->isEmpty());
    }

    public function testMapToUniqueArray()
    {
        $collection = new Collection(MockDto::class, [
            (new MockDto())->setName('Test'),
            (new MockDto())->setName('Test'),
            (new MockDto())->setName('Test 2'),
        ]);

        $array = $collection->mapToUniqueArray(static function (MockDto $dto) {
            return $dto->getName();
        });

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
        $this->assertContains('Test', $array);
        $this->assertContains('Test 2', $array);
    }

    public function testMapToArray()
    {
        $collection = new Collection(MockDto::class, [
            (new MockDto())->setName('Test'),
            (new MockDto())->setName('Test'),
            (new MockDto())->setName('Test 2'),
        ]);

        $array = $collection->mapToArray(static function (MockDto $dto) {
            return $dto->getName();
        });

        $this->assertIsArray($array);
        $this->assertCount(3, $array);
        $this->assertContains('Test', $array);
        $this->assertContains('Test 2', $array);
    }

    public function testFind()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $dto = $collection->find(static function (MockDto $dto) {
            return $dto->getName() === 'Test';
        });

        $this->assertInstanceOf(MockDto::class, $dto);
        $this->assertSame($dto1, $dto);

        $missing = $collection->find(static function (MockDto $dto) {
            return $dto->getName() === 'Test 3';
        });

        $this->assertNull($missing);
    }

    public function testChunk()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $chunked = $collection->chunk(2);

        $this->assertIsArray($chunked);
        $this->assertCount(2, $chunked);

        $this->assertInstanceOf(Collection::class, $chunked[0]);
        $this->assertSame($dto1, $chunked[0]->get(0));
        $this->assertSame($dto2, $chunked[0]->get(1));

        $this->assertInstanceOf(Collection::class, $chunked[1]);
        $this->assertSame($dto3, $chunked[1]->get(0));
    }

    public function testFilter()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $filtered = $collection->filter(static function (MockDto $dto) {
            return $dto->getName() === 'Test';
        });

        $this->assertInstanceOf(Collection::class, $filtered);
        $this->assertNotSame($collection, $filtered);
        $this->assertCount(2, $filtered);
        $this->assertSame($dto1, $filtered->get(0));
        $this->assertSame($dto3, $filtered->get(1));

        $notFiltered = $collection->filter(static function (MockDto $dto) {
            return $dto->getName() === 'Test 3';
        });

        $this->assertInstanceOf(Collection::class, $notFiltered);
        $this->assertCount(0, $notFiltered);
    }

    public function testMatchAll()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');
        $dto4 = (new MockDto())->setName('Test 3');
        $dto5 = new MockBasicDto();

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $matched = $collection->matchAll($dto1, $comparator);

        $this->assertInstanceOf(Collection::class, $matched);
        $this->assertNotSame($collection, $matched);
        $this->assertCount(2, $matched);
        $this->assertSame($dto1, $matched->get(0));
        $this->assertSame($dto3, $matched->get(1));

        $notMatched = $collection->matchAll($dto4, $comparator);

        $this->assertInstanceOf(Collection::class, $notMatched);
        $this->assertNotSame($collection, $notMatched);
        $this->assertCount(0, $notMatched);

        $cannotMatch = $collection->matchAll($dto5, $comparator);

        $this->assertInstanceOf(Collection::class, $cannotMatch);
        $this->assertNotSame($collection, $cannotMatch);
        $this->assertCount(0, $cannotMatch);
    }

    public function testMatch()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');
        $dto4 = (new MockDto())->setName('Test 3');
        $dto5 = new MockBasicDto();

        $collection = new Collection(MockDto::class, [
            $dto2,
            $dto3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $dto = $collection->match($dto1, $comparator);

        $this->assertInstanceOf(MockDto::class, $dto);
        $this->assertSame($dto3, $dto);

        $this->assertNull($collection->match($dto4, $comparator));

        $this->assertNull($collection->match($dto5, $comparator));
    }

    public function testSplit()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $split = $collection->split(function (MockDto $dto) {
            return $dto->getName();
        });

        $this->assertIsArray($split);
        $this->assertCount(2, $split);

        $this->assertArrayHasKey('Test', $split);
        $this->assertInstanceOf(Collection::class, $split['Test']);
        $this->assertCount(2, $split['Test']);
        $this->assertSame($dto1, $split['Test']->get(0));
        $this->assertSame($dto3, $split['Test']->get(1));

        $this->assertArrayHasKey('Test 2', $split);
        $this->assertCount(1, $split['Test 2']);
        $this->assertSame($dto2, $split['Test 2']->get(0));
    }

    public function testMerge()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = new Collection(MockDto::class, [
            $dto1,
            $dto2,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $this->assertSame($collection1, $collection1->merge($collection1));
        $this->assertSame($collection1, $collection1->merge(new Collection(MockDto::class)));

        $merged = $collection1->merge($collection1, new Collection(MockDto::class), $collection2);

        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertEquals($collection1->getDtoType(), $merged->getDtoType());
        $this->assertCount(3, $merged);
        $this->assertSame($dto1, $merged->get(0));
        $this->assertSame($dto2, $merged->get(1));
        $this->assertSame($dto3, $merged->get(2));

        /**
         * A collection of {@see MockDto} can be merged into a collection of {@see MockDtoInterface} as
         * {@see MockDtoInterface} is a superset of {@see MockDto}, however the inverse is not true
         */
        $collection3 = new Collection(MockDtoInterface::class);

        $merged = $collection3->merge($collection1);
        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertEquals($collection3->getDtoType(), $merged->getDtoType());
        $this->assertCount(2, $merged);
        $this->assertSame($dto1, $merged->get(0));
        $this->assertSame($dto2, $merged->get(1));
    }

    public static function mergeIncompatibleCollectionTypeProvider(): array
    {
        return [
            [MockBasicDto::class],
            [MockDtoInterface::class],
        ];
    }

    #[DataProvider('mergeIncompatibleCollectionTypeProvider')]
    public function testMergeIncompatibleCollectionType()
    {
        $collection1 = new Collection(MockDto::class);
        $collection2 = new Collection(MockBasicDto::class);

        $this->expectException(IncompatibleCollectionException::class);

        $collection1->merge($collection2);
    }

    public function testDiff()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $diff = $collection1->diff($collection2);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection1, $diff);
        $this->assertCount(2, $diff);
        $this->assertSame($dto1, $diff->get(0));
        $this->assertSame($dto2, $diff->get(1));
    }

    public function testDiffWithSelf()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $diff = $collection->diff($collection);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection, $diff);
        $this->assertCount(0, $diff);
    }

    public function testDiffWithComparator()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $diff = $collection1->diff($collection2, $comparator);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection1, $diff);
        $this->assertCount(1, $diff);
        $this->assertSame($dto2, $diff->get(0));
    }

    public function testDiffWithDifferentCollectionType()
    {
        $dto = (new MockDto())->setName('Test');
        $collection1 = new Collection(MockDto::class, [$dto]);
        $collection2 = new Collection(MockCustomSerializationDto::class);

        $diff = $collection1->diff($collection2);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertSame($collection1, $diff);
    }

    public function testIntersect()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(1, $intersect);
        $this->assertSame($dto3, $intersect->get(0));
    }

    public function testIntersectWithSelf()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $intersect = $collection->intersect($collection);

        $this->assertSame($collection, $intersect);
    }

    public function testIntersectWithComparator()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $intersect = $collection1->intersect($collection2, $comparator);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(2, $intersect);
        $this->assertSame($dto1, $intersect->get(0));
        $this->assertSame($dto3, $intersect->get(1));
    }

    public function testIntersectWithDifferentCollectionType()
    {
        $dto = (new MockDto())->setName('Test');
        $collection1 = new Collection(MockDto::class, [$dto]);
        $collection2 = new Collection(MockCustomSerializationDto::class);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(0, $intersect);
    }

    public function testIteration()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        foreach ($collection as $index => $dto) {
            $this->assertInstanceOf(MockDto::class, $dto);
            $this->assertSame($collection->get($index), $dto);
        }
    }

    public function testAddEntity()
    {
        $dto = new MockDto();
        $emptyCollection = new Collection(MockDto::class);
        $collection = $emptyCollection->add($dto);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(1, $collection->count());
    }

    public function testAddEntityAlreadyInCollection()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);

        $newCollection = $collection->add($dto);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertSame($collection, $newCollection);
    }

    public function testAddMultipleEntities()
    {
        $emptyCollection = new Collection(MockDto::class);
        $collection = $emptyCollection->add(new MockDto(), new MockDto());

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testAddInvalidEntityType()
    {
        $collection = new Collection(MockDto::class);

        $this->expectException(IncompatibleDtoException::class);

        $collection->add(new MockBasicDto());
    }

    public function testRemoveEntity()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);

        $newCollection = $collection->remove($dto);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveMultipleEntities()
    {
        $dto1 = new MockDto();
        $dto2 = new MockDto();
        $collection = new Collection(MockDto::class, [$dto1, $dto2]);

        $newCollection = $collection->remove($dto1, $dto2);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveEntityNotInCollection()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class);

        $this->expectException(NotInCollectionException::class);

        $collection->remove($dto);
    }

    public function testReplaceEntity()
    {
        $oldEntity = new MockDto(['name' => 'Test']);
        $newEntity = new MockDto(['name' => 'Test 2']);

        $collection = new Collection(MockDto::class, [new MockDto(), $oldEntity]);

        $newCollection = $collection->replace($oldEntity, $newEntity);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(2, $newCollection->count());
        $this->assertSame($newEntity, $newCollection->get(1));
    }

    public function testReplaceEntityNotInCollection()
    {
        $oldEntity = new MockDto(['name' => 'Test']);
        $newEntity = new MockDto(['name' => 'Test 2']);
        $collection = new Collection(MockDto::class, [$newEntity]);

        $this->expectException(NotInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testReplaceEntityWithInvalidType()
    {
        $validEntity = new MockDto();
        $collection = new Collection(MockDto::class, [$validEntity]);

        $this->expectException(IncompatibleDtoException::class);

        $collection->replace($validEntity, new MockBasicDto());
    }

    public function testReplaceEntityWithEntityAlreadyInCollection()
    {
        $oldEntity = new MockDto(['name' => 'Test']);
        $newEntity = new MockDto(['name' => 'Test 2']);

        $collection = new Collection(MockDto::class, [$oldEntity, $newEntity]);

        $this->expectException(AlreadyInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testGet()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);

        $this->assertSame($dto, $collection->get(0));
    }

    public static function getInvalidIndexProvider(): array
    {
        return [
            [
                new Collection(MockDto::class),
                0,
            ],
            [
                new Collection(MockDto::class),
                -1,
            ],
            [
                new Collection(MockDto::class, [new MockDto()]),
                10,
            ],
        ];
    }

    #[DataProvider('getInvalidIndexProvider')]
    public function testGetInvalidIndex(Collection $collection, int $index)
    {
        $this->expectException(InvalidIndexException::class);

        $collection->get($index);
    }

    public function testCount()
    {
        $collection = new Collection(MockDto::class, [
            new MockDto(),
        ]);

        $this->assertEquals(1, $collection->count());
        $this->assertCount(1, $collection);
    }

    public function testMap()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $mapped = $collection->map(static function (MockDto $dto): MockDto {
            return $dto->setTitle('Map');
        });

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());

        foreach ($mapped as $i => $dto) {
            $this->assertInstanceOf(MockDto::class, $dto);
            $this->assertEquals($collection->get($i)->getName(), $dto->getName());
            $this->assertEquals('Map', $dto->getTitle());
        }
    }

    public function testMapToNewEntityType()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection = new Collection(MockDto::class, [
            $dto1,
            $dto2,
            $dto3,
        ]);

        $mapped = $collection->map(static function (MockDto $dto): MockBasicDto {
            return (new MockBasicDto())->setName($dto->getName());
        }, MockBasicDto::class);

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());
        $this->assertEquals(MockBasicDto::class, $mapped->getDtoType());

        foreach ($mapped as $i => $dto) {
            $this->assertInstanceOf(MockBasicDto::class, $dto);
            $this->assertEquals($collection->get($i)->getName(), $dto->getName());
        }
    }

    public function testToArray()
    {
        $dto = new MockDto();
        $collection = new Collection(MockDto::class, [$dto]);

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertContains($dto, $array);
    }
}
