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
use Exterrestris\DtoFramework\Tests\Mocks\TestDto;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntityInterface;
use Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;
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
    public function testConstructWithInvalidType(string $entityType)
    {
        $this->expectException(InvalidTypeException::class);

        new Collection($entityType);
    }

    public function testConstructWithValidItems()
    {
        $collection = new Collection(TestEntity::class, [new TestEntity()]);

        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function testConstructWithInvalidItems()
    {
        $this->expectException(IncompatibleDtoException::class);

        new Collection(TestEntity::class, [new TestDto()]);
    }

    public function testIsOfType()
    {
        $specificCollection = new Collection(TestEntity::class);
        $typeCollection = new Collection(TestEntityInterface::class);
        $genericCollection = new Collection(ProcessableDtoInterface::class);

        $this->assertTrue($specificCollection->isOfType(TestEntity::class));
        $this->assertTrue($specificCollection->isOfType(TestEntityInterface::class));
        $this->assertTrue($specificCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($specificCollection->isOfType(ProcessableDtoInterface::class));

        $this->assertFalse($typeCollection->isOfType(TestEntity::class));
        $this->assertTrue($typeCollection->isOfType(TestEntityInterface::class));
        $this->assertFalse($typeCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($typeCollection->isOfType(ProcessableDtoInterface::class));

        $this->assertFalse($genericCollection->isOfType(TestEntity::class));
        $this->assertFalse($genericCollection->isOfType(TestEntityInterface::class));
        $this->assertFalse($genericCollection->isOfType(AbstractProcessableDto::class));
        $this->assertTrue($genericCollection->isOfType(ProcessableDtoInterface::class));
    }

    public function testGetEntityType()
    {
        $collection = new Collection(TestEntity::class);

        $this->assertEquals(TestEntity::class, $collection->getDtoType());
    }

    public function testContains()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);

        $this->assertTrue($collection->contains($entity));
        $this->assertFalse($collection->contains(new TestEntity()));
    }

    public function testClear()
    {
        $collection = new Collection(TestEntity::class, [new TestEntity()]);
        $cleared = $collection->clear();

        $this->assertInstanceOf(Collection::class, $cleared);
        $this->assertNotSame($collection, $cleared);
        $this->assertEquals(0, $cleared->count());
        $this->assertEmpty($cleared->toArray());
    }

    public function testFirst()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);
        $emptyCollection = new Collection(TestEntity::class);

        $this->assertSame($entity, $collection->first());
        $this->assertNull($emptyCollection->first());
    }

    public function testIsEmpty()
    {
        $emptyCollection = new Collection(TestEntity::class);
        $collection = new Collection(TestEntity::class, [new TestEntity()]);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertFalse($collection->isEmpty());
    }

    public function testMapToUniqueArray()
    {
        $collection = new Collection(TestEntity::class, [
            (new TestEntity())->setName('Test'),
            (new TestEntity())->setName('Test'),
            (new TestEntity())->setName('Test 2'),
        ]);

        $array = $collection->mapToUniqueArray(static function (TestEntity $entity) {
            return $entity->getName();
        });

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
        $this->assertContains('Test', $array);
        $this->assertContains('Test 2', $array);
    }

    public function testMapToArray()
    {
        $collection = new Collection(TestEntity::class, [
            (new TestEntity())->setName('Test'),
            (new TestEntity())->setName('Test'),
            (new TestEntity())->setName('Test 2'),
        ]);

        $array = $collection->mapToArray(static function (TestEntity $entity) {
            return $entity->getName();
        });

        $this->assertIsArray($array);
        $this->assertCount(3, $array);
        $this->assertContains('Test', $array);
        $this->assertContains('Test 2', $array);
    }

    public function testFind()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $entity = $collection->find(static function (TestEntity $entity) {
            return $entity->getName() === 'Test';
        });

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertSame($entity1, $entity);

        $missing = $collection->find(static function (TestEntity $entity) {
            return $entity->getName() === 'Test 3';
        });

        $this->assertNull($missing);
    }

    public function testChunk()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $chunked = $collection->chunk(2);

        $this->assertIsArray($chunked);
        $this->assertCount(2, $chunked);

        $this->assertInstanceOf(Collection::class, $chunked[0]);
        $this->assertSame($entity1, $chunked[0]->get(0));
        $this->assertSame($entity2, $chunked[0]->get(1));

        $this->assertInstanceOf(Collection::class, $chunked[1]);
        $this->assertSame($entity3, $chunked[1]->get(0));
    }

    public function testFilter()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $filtered = $collection->filter(static function (TestEntity $entity) {
            return $entity->getName() === 'Test';
        });

        $this->assertInstanceOf(Collection::class, $filtered);
        $this->assertNotSame($collection, $filtered);
        $this->assertCount(2, $filtered);
        $this->assertSame($entity1, $filtered->get(0));
        $this->assertSame($entity3, $filtered->get(1));

        $notFiltered = $collection->filter(static function (TestEntity $entity) {
            return $entity->getName() === 'Test 3';
        });

        $this->assertInstanceOf(Collection::class, $notFiltered);
        $this->assertCount(0, $notFiltered);
    }

    public function testMatchAll()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');
        $entity4 = (new TestEntity())->setName('Test 3');
        $entity5 = new TestDto();

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $matched = $collection->matchAll($entity1, $comparator);

        $this->assertInstanceOf(Collection::class, $matched);
        $this->assertNotSame($collection, $matched);
        $this->assertCount(2, $matched);
        $this->assertSame($entity1, $matched->get(0));
        $this->assertSame($entity3, $matched->get(1));

        $notMatched = $collection->matchAll($entity4, $comparator);

        $this->assertInstanceOf(Collection::class, $notMatched);
        $this->assertNotSame($collection, $notMatched);
        $this->assertCount(0, $notMatched);

        $cannotMatch = $collection->matchAll($entity5, $comparator);

        $this->assertInstanceOf(Collection::class, $cannotMatch);
        $this->assertNotSame($collection, $cannotMatch);
        $this->assertCount(0, $cannotMatch);
    }

    public function testMatch()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');
        $entity4 = (new TestEntity())->setName('Test 3');
        $entity5 = new TestDto();

        $collection = new Collection(TestEntity::class, [
            $entity2,
            $entity3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $entity = $collection->match($entity1, $comparator);

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertSame($entity3, $entity);

        $this->assertNull($collection->match($entity4, $comparator));

        $this->assertNull($collection->match($entity5, $comparator));
    }

    public function testSplit()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $split = $collection->split(function (TestEntity $entity) {
            return $entity->getName();
        });

        $this->assertIsArray($split);
        $this->assertCount(2, $split);

        $this->assertArrayHasKey('Test', $split);
        $this->assertInstanceOf(Collection::class, $split['Test']);
        $this->assertCount(2, $split['Test']);
        $this->assertSame($entity1, $split['Test']->get(0));
        $this->assertSame($entity3, $split['Test']->get(1));

        $this->assertArrayHasKey('Test 2', $split);
        $this->assertCount(1, $split['Test 2']);
        $this->assertSame($entity2, $split['Test 2']->get(0));
    }

    public function testMerge()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $this->assertSame($collection1, $collection1->merge($collection1));
        $this->assertSame($collection1, $collection1->merge(new Collection(TestEntity::class)));

        $merged = $collection1->merge($collection1, new Collection(TestEntity::class), $collection2);

        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertEquals($collection1->getDtoType(), $merged->getDtoType());
        $this->assertCount(3, $merged);
        $this->assertSame($entity1, $merged->get(0));
        $this->assertSame($entity2, $merged->get(1));
        $this->assertSame($entity3, $merged->get(2));

        /**
         * A collection of {@see TestEntity} can be merged into a collection of {@see TestEntityInterface} as
         * {@see TestEntityInterface} is a superset of {@see TestEntity}, however the inverse is not true
         */
        $collection3 = new Collection(TestEntityInterface::class);

        $merged = $collection3->merge($collection1);
        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertEquals($collection3->getDtoType(), $merged->getDtoType());
        $this->assertCount(2, $merged);
        $this->assertSame($entity1, $merged->get(0));
        $this->assertSame($entity2, $merged->get(1));
    }

    public static function mergeIncompatibleCollectionTypeProvider(): array
    {
        return [
            [TestDto::class],
            [TestEntityInterface::class],
        ];
    }

    #[DataProvider('mergeIncompatibleCollectionTypeProvider')]
    public function testMergeIncompatibleCollectionType()
    {
        $collection1 = new Collection(TestEntity::class);
        $collection2 = new Collection(TestDto::class);

        $this->expectException(IncompatibleCollectionException::class);

        $collection1->merge($collection2);
    }

    public function testDiff()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $diff = $collection1->diff($collection2);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection1, $diff);
        $this->assertCount(2, $diff);
        $this->assertSame($entity1, $diff->get(0));
        $this->assertSame($entity2, $diff->get(1));
    }

    public function testDiffWithSelf()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $diff = $collection->diff($collection);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection, $diff);
        $this->assertCount(0, $diff);
    }

    public function testDiffWithComparator()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $diff = $collection1->diff($collection2, $comparator);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertNotSame($collection1, $diff);
        $this->assertCount(1, $diff);
        $this->assertSame($entity2, $diff->get(0));
    }

    public function testDiffWithDifferentCollectionType()
    {
        $entity = (new TestEntity())->setName('Test');
        $collection1 = new Collection(TestEntity::class, [$entity]);
        $collection2 = new Collection(CustomSerializationEntity::class);

        $diff = $collection1->diff($collection2);

        $this->assertInstanceOf(Collection::class, $diff);
        $this->assertSame($collection1, $diff);
    }

    public function testIntersect()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(1, $intersect);
        $this->assertSame($entity3, $intersect->get(0));
    }

    public function testIntersectWithSelf()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $intersect = $collection->intersect($collection);

        $this->assertSame($collection, $intersect);
    }

    public function testIntersectWithComparator()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $comparator = new EquivalentComparator(new DataExtractor());

        $intersect = $collection1->intersect($collection2, $comparator);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(2, $intersect);
        $this->assertSame($entity1, $intersect->get(0));
        $this->assertSame($entity3, $intersect->get(1));
    }

    public function testIntersectWithDifferentCollectionType()
    {
        $entity = (new TestEntity())->setName('Test');
        $collection1 = new Collection(TestEntity::class, [$entity]);
        $collection2 = new Collection(CustomSerializationEntity::class);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(0, $intersect);
    }

    public function testIteration()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        foreach ($collection as $index => $entity) {
            $this->assertInstanceOf(TestEntity::class, $entity);
            $this->assertSame($collection->get($index), $entity);
        }
    }

    public function testAddEntity()
    {
        $entity = new TestEntity();
        $emptyCollection = new Collection(TestEntity::class);
        $collection = $emptyCollection->add($entity);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(1, $collection->count());
    }

    public function testAddEntityAlreadyInCollection()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);

        $newCollection = $collection->add($entity);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertSame($collection, $newCollection);
    }

    public function testAddMultipleEntities()
    {
        $emptyCollection = new Collection(TestEntity::class);
        $collection = $emptyCollection->add(new TestEntity(), new TestEntity());

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testAddInvalidEntityType()
    {
        $collection = new Collection(TestEntity::class);

        $this->expectException(IncompatibleDtoException::class);

        $collection->add(new TestDto());
    }

    public function testRemoveEntity()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);

        $newCollection = $collection->remove($entity);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveMultipleEntities()
    {
        $entity1 = new TestEntity();
        $entity2 = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity1, $entity2]);

        $newCollection = $collection->remove($entity1, $entity2);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveEntityNotInCollection()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class);

        $this->expectException(NotInCollectionException::class);

        $collection->remove($entity);
    }

    public function testReplaceEntity()
    {
        $oldEntity = new TestEntity(['name' => 'Test']);
        $newEntity = new TestEntity(['name' => 'Test 2']);

        $collection = new Collection(TestEntity::class, [new TestEntity(), $oldEntity]);

        $newCollection = $collection->replace($oldEntity, $newEntity);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(2, $newCollection->count());
        $this->assertSame($newEntity, $newCollection->get(1));
    }

    public function testReplaceEntityNotInCollection()
    {
        $oldEntity = new TestEntity(['name' => 'Test']);
        $newEntity = new TestEntity(['name' => 'Test 2']);
        $collection = new Collection(TestEntity::class, [$newEntity]);

        $this->expectException(NotInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testReplaceEntityWithInvalidType()
    {
        $validEntity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$validEntity]);

        $this->expectException(IncompatibleDtoException::class);

        $collection->replace($validEntity, new TestDto());
    }

    public function testReplaceEntityWithEntityAlreadyInCollection()
    {
        $oldEntity = new TestEntity(['name' => 'Test']);
        $newEntity = new TestEntity(['name' => 'Test 2']);

        $collection = new Collection(TestEntity::class, [$oldEntity, $newEntity]);

        $this->expectException(AlreadyInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testGet()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);

        $this->assertSame($entity, $collection->get(0));
    }

    public static function getInvalidIndexProvider(): array
    {
        return [
            [
                new Collection(TestEntity::class),
                0,
            ],
            [
                new Collection(TestEntity::class),
                -1,
            ],
            [
                new Collection(TestEntity::class, [new TestEntity()]),
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
        $collection = new Collection(TestEntity::class, [
            new TestEntity(),
        ]);

        $this->assertEquals(1, $collection->count());
        $this->assertCount(1, $collection);
    }

    public function testMap()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $mapped = $collection->map(static function (TestEntity $entity): TestEntity {
            return $entity->setTitle('Map');
        });

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());

        foreach ($mapped as $i => $entity) {
            $this->assertInstanceOf(TestEntity::class, $entity);
            $this->assertEquals($collection->get($i)->getName(), $entity->getName());
            $this->assertEquals('Map', $entity->getTitle());
        }
    }

    public function testMapToNewEntityType()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection = new Collection(TestEntity::class, [
            $entity1,
            $entity2,
            $entity3,
        ]);

        $mapped = $collection->map(static function (TestEntity $entity): TestDto {
            return (new TestDto())->setName($entity->getName());
        }, TestDto::class);

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());
        $this->assertEquals(TestDto::class, $mapped->getDtoType());

        foreach ($mapped as $i => $entity) {
            $this->assertInstanceOf(TestDto::class, $entity);
            $this->assertEquals($collection->get($i)->getName(), $entity->getName());
        }
    }

    public function testToArray()
    {
        $entity = new TestEntity();
        $collection = new Collection(TestEntity::class, [$entity]);

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertContains($entity, $array);
    }
}
