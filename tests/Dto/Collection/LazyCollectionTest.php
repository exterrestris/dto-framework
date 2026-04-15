<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Collection;

use Exterrestris\DtoFramework\Dto\Collection\LazyCollection;
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
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractor;
use Exterrestris\DtoFramework\Tests\Mocks\TestDto;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntityInterface;
use Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(LazyCollection::class)]
class LazyCollectionTest extends TestCase
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

        new LazyCollection($entityType, new Factory(), static function () {
            yield new TestEntity();
        });
    }

    public function testIsOfType()
    {
        $collection = static::createLazyCollection(5);

        $this->assertTrue($collection->isOfType(TestEntity::class));
        $this->assertTrue($collection->isOfType(ProcessableDtoInterface::class));
        $this->assertTrue($collection->isOfType(AbstractProcessableDto::class));
        $this->assertFalse($collection->isOfType(TestDto::class));
    }

    public function testGetEntityType()
    {
        $collection = static::createLazyCollection(5);

        $this->assertEquals(TestEntity::class, $collection->getDtoType());
    }

    public function testContains()
    {
        $entity = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$entity]);

        $this->assertTrue($collection->contains($entity));
        $this->assertFalse($collection->contains(new TestEntity()));
    }

    public function testClear()
    {
        $collection = static::createLazyCollection(5);
        $cleared = $collection->clear();

        $this->assertInstanceOf(Collection::class, $cleared);
        $this->assertNotSame($collection, $cleared);
        $this->assertEquals(0, $cleared->count());
        $this->assertEmpty($cleared->toArray());
    }

    public function testFirst()
    {
        $collection = static::createLazyCollection(5);
        $emptyCollection = static::createLazyCollection(0);

        $this->assertSame($collection->get(0), $collection->first());
        $this->assertNull($emptyCollection->first());
    }

    public function testIsEmpty()
    {
        $emptyCollection = static::createLazyCollection(0);
        $knownSizeCollection = static::createLazyCollection(5);
        $unknownSizeCollection = static::createLazyCollection(5, false);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertFalse($knownSizeCollection->isEmpty());
        $this->assertFalse($unknownSizeCollection->isEmpty());
    }

    public function testMapToUniqueArray()
    {
        $collection = static::createLazyCollectionFromEntities([
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
        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
            $entity1,
            $entity2,
        ]);

        $collection2 = new Collection(TestEntity::class, [
            $entity3,
        ]);

        $collection3 = new Collection(TestEntity::class, [
            $entity1,
        ]);

        $this->assertSame($collection1, $collection1->merge($collection1));
        $this->assertSame($collection1, $collection1->merge(new Collection(TestEntity::class)));
        $this->assertSame($collection1, $collection1->merge($collection3));


        $merged = $collection1->merge($collection1, new Collection(TestEntity::class), $collection2, $collection3);

        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertCount(3, $merged);
        $this->assertSame($entity1, $merged->get(0));
        $this->assertSame($entity2, $merged->get(1));
        $this->assertSame($entity3, $merged->get(2));
    }

    public function testMergeDifferentCollectionType()
    {
        $collection1 = static::createLazyCollection(5);
        $collection2 = new Collection(CustomSerializationEntity::class);

        $this->expectException(IncompatibleCollectionException::class);

        $collection1->merge($collection2);
    }

    public function testDiff()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
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
        $collection1 = static::createLazyCollectionFromEntities([$entity]);
        $collection2 = new Collection(CustomSerializationEntity::class);

        $diff = $collection1->diff($collection2);

        $this->assertSame($collection1, $diff);
    }

    public function testIntersect()
    {
        $entity1 = (new TestEntity())->setName('Test');
        $entity2 = (new TestEntity())->setName('Test 2');
        $entity3 = (new TestEntity())->setName('Test');

        $collection1 = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
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
        $collection1 = static::createLazyCollectionFromEntities([$entity]);
        $collection2 = new Collection(CustomSerializationEntity::class);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(0, $intersect);
    }

    public function testIteration()
    {
        $collection = static::createLazyCollection(5);

        foreach ($collection as $index => $entity) {
            $this->assertInstanceOf(TestEntity::class, $entity);
            $this->assertSame($collection->get($index), $entity);
        }

        // Ensure can reiterate over generator

        foreach ($collection as $index => $entity) {
            $this->assertInstanceOf(TestEntity::class, $entity);
            $this->assertSame($collection->get($index), $entity);
        }
    }

    public function testAddEntity()
    {
        $entity = new TestEntity();
        $emptyCollection = static::createLazyCollection(0);
        $collection = $emptyCollection->add($entity);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(1, $collection->count());
    }

    public function testAddEntityAlreadyInCollection()
    {
        $entity = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$entity]);

        $newCollection = $collection->add($entity);

        $this->assertSame($collection, $newCollection);
    }

    public function testAddMultipleEntities()
    {
        $emptyCollection = static::createLazyCollection(0);
        $collection = $emptyCollection->add(new TestEntity(), new TestEntity());

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testAddInvalidEntity()
    {
        $collection = static::createLazyCollection(5);

        $this->expectException(IncompatibleDtoException::class);

        $collection->add(new TestDto());
    }

    public function testRemoveEntity()
    {
        $entity = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$entity]);

        $newCollection = $collection->remove($entity);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveMultipleEntities()
    {
        $entity1 = new TestEntity();
        $entity2 = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$entity1, $entity2]);

        $newCollection = $collection->remove($entity1, $entity2);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveEntityNotInCollection()
    {
        $entity = new TestEntity();
        $collection = static::createLazyCollection(5);

        $this->expectException(NotInCollectionException::class);

        $collection->remove($entity);
    }

    public function testReplaceEntity()
    {
        $oldEntity = new TestEntity(['name' => 'Test']);
        $newEntity = new TestEntity(['name' => 'Test 2']);

        $collection = static::createLazyCollectionFromEntities([new TestEntity(), $oldEntity]);

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
        $collection = static::createLazyCollectionFromEntities([$newEntity]);

        $this->expectException(NotInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testReplaceEntityWithInvalidType()
    {
        $validEntity = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$validEntity]);

        $this->expectException(IncompatibleDtoException::class);

        $collection->replace($validEntity, new TestDto());
    }

    public function testReplaceEntityWithEntityAlreadyInCollection()
    {
        $oldEntity = new TestEntity(['name' => 'Test']);
        $newEntity = new TestEntity(['name' => 'Test 2']);

        $collection = static::createLazyCollectionFromEntities([$oldEntity, $newEntity]);

        $this->expectException(AlreadyInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testGet()
    {
        $entity = new TestEntity();
        $collection = static::createLazyCollectionFromEntities([$entity]);

        $this->assertSame($entity, $collection->get(0));
    }

    public static function getInvalidIndexProvider(): array
    {
        return [
            [
                static::createLazyCollection(0),
                0,
            ],
            [
                static::createLazyCollection(5),
                -1,
            ],
            [
                static::createLazyCollection(5),
                10,
            ],
            [
                static::createLazyCollection(4, false),
                10,
            ],
        ];
    }

    #[DataProvider('getInvalidIndexProvider')]
    public function testGetInvalidIndex(LazyCollection $collection, int $index)
    {
        $this->expectException(InvalidIndexException::class);

        $collection->get($index);
    }

    public function testCount()
    {
        $knownSizeCollection = static::createLazyCollection(10);
        $unknownSizeCollection1 = static::createLazyCollection(6, false);
        $unknownSizeCollection2 = static::createLazyCollection(7, false);

        $this->assertEquals(10, $knownSizeCollection->count());
        $this->assertCount(10, $knownSizeCollection);

        $this->assertEquals(6, $unknownSizeCollection1->count());
        $this->assertCount(7, $unknownSizeCollection2);
    }

    public function testIsCountKnown()
    {
        $knownSizeCollection = static::createLazyCollection(5);
        $unknownSizeCollection1 = static::createLazyCollection(6, false);
        $unknownSizeCollection2 = static::createLazyCollection(7, false);
        $unknownSizeCollection3 = static::createLazyCollection(8, false);
        $unknownSizeCollection4 = static::createLazyCollection(9, false);

        $this->assertTrue($knownSizeCollection->isCountKnown());

        $this->assertFalse($unknownSizeCollection1->isCountKnown());
        $this->assertEquals(6, $unknownSizeCollection1->count());
        $this->assertTrue($unknownSizeCollection1->isCountKnown());

        $this->assertFalse($unknownSizeCollection2->isCountKnown());
        $this->assertCount(7, $unknownSizeCollection2);
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertTrue($unknownSizeCollection2->isCountKnown());

        $this->assertFalse($unknownSizeCollection3->isCountKnown());
        /** @noinspection PhpStatementHasEmptyBodyInspection */
        foreach ($unknownSizeCollection3 as $ignored) {}
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertTrue($unknownSizeCollection3->isCountKnown());

        $this->assertFalse($unknownSizeCollection4->isCountKnown());
        $unknownSizeCollection4->toArray();
        $this->assertTrue($unknownSizeCollection4->isCountKnown());
    }

    public function testMap()
    {
        $collection = static::createLazyCollection(5);

        $mapped = $collection->map(static function (TestEntity $entity): TestEntity {
            return $entity->setTitle('Map');
        });

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());

        foreach ($mapped as $i => $entity) {
            $this->assertInstanceOf(TestEntity::class, $entity);
            $this->assertEquals($collection->get($i)->getName(), $entity->getName());
            $this->assertEquals('Map', $entity->getTitle());
        }
    }

    public function testMapToNewEntityType()
    {
        $collection = static::createLazyCollection(5);

        $mapped = $collection->map(static function (TestEntity $entity): CustomSerializationEntity {
            return (new CustomSerializationEntity())->setName($entity->getName());
        }, CustomSerializationEntity::class);

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());
        $this->assertEquals(CustomSerializationEntity::class, $mapped->getDtoType());

        foreach ($mapped as $i => $entity) {
            $this->assertInstanceOf(CustomSerializationEntity::class, $entity);
            $this->assertEquals($collection->get($i)->getName(), $entity->getName());
        }
    }

    public function testToArray()
    {
        $collection = static::createLazyCollection(5);

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertCount(5, $array);
    }

    private static function createLazyCollectionFromEntities(array $entities, bool $knownSize = true): LazyCollection
    {
        return new LazyCollection(
            TestEntity::class,
            new Factory(),
            static function () use ($entities) {
                foreach ($entities as $i => $entity) {
                    yield $i => $entity;
                }
            },
            $knownSize ? count($entities) : null,
        );
    }

    private static function createLazyCollection(int $size, bool $knownSize = true): LazyCollection
    {
        $entities = [];

        for ($i = 0; $i < $size; $i++) {
            $entities[] = (new TestEntity())->setName(sprintf('Item %s', $size + 1));
        }

        return static::createLazyCollectionFromEntities($entities, $knownSize);
    }
}
