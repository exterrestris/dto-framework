<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Collection;

use Exterrestris\DtoFramework\Comparison\Comparator\AbstractComparator;
use Exterrestris\DtoFramework\Comparison\Comparator\EquivalentComparator;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exception\AlreadyInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\IncompatibleCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\IncompatibleItemException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\NotInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\LazyCollection;
use Exterrestris\DtoFramework\Dto\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\Dto\AbstractProcessableDto;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\AbstractFactory;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractor;
use Exterrestris\DtoFramework\Serialization\Rule\Map;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockBasicDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Utility\GetShortDtoTypeTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LazyCollection::class)]
#[UsesClass(AbstractDto::class)]
#[UsesClass(DataExtractor::class)]
#[UsesClass(Collection::class)]
#[UsesClass(AbstractFactory::class)]
#[UsesClass(Factory::class)]
#[UsesClass(IncompatibleCollectionException::class)]
#[UsesClass(IncompatibleItemException::class)]
#[UsesClass(GetShortDtoTypeTrait::class)]
#[UsesClass(EquivalentComparator::class)]
#[UsesClass(Map::class)]
#[UsesClass(IncompatibleDtoException::class)]
#[UsesClass(AbstractComparator::class)]
#[UsesClass(AlreadyInCollectionException::class)]
#[UsesClass(NotInCollectionException::class)]
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
    public function testConstructWithInvalidType(string $dtoType)
    {
        $this->expectException(InvalidTypeException::class);

        new LazyCollection($dtoType, new Factory(), static function () {
            yield new MockDto();
        });
    }

    public function testIsOfType()
    {
        $collection = static::createLazyCollection(5);

        $this->assertTrue($collection->isOfType(MockDto::class));
        $this->assertTrue($collection->isOfType(ProcessableDtoInterface::class));
        $this->assertTrue($collection->isOfType(AbstractProcessableDto::class));
        $this->assertFalse($collection->isOfType(MockBasicDto::class));
    }

    public function testGetEntityType()
    {
        $collection = static::createLazyCollection(5);

        $this->assertEquals(MockDto::class, $collection->getDtoType());
    }

    public function testContains()
    {
        $dto = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$dto]);

        $this->assertTrue($collection->contains($dto));
        $this->assertFalse($collection->contains(new MockDto()));
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
        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
            $dto1,
            $dto2,
        ]);

        $collection2 = new Collection(MockDto::class, [
            $dto3,
        ]);

        $collection3 = new Collection(MockDto::class, [
            $dto1,
        ]);

        $this->assertSame($collection1, $collection1->merge($collection1));
        $this->assertSame($collection1, $collection1->merge(new Collection(MockDto::class)));
        $this->assertSame($collection1, $collection1->merge($collection3));


        $merged = $collection1->merge($collection1, new Collection(MockDto::class), $collection2, $collection3);

        $this->assertInstanceOf(Collection::class, $merged);
        $this->assertCount(3, $merged);
        $this->assertSame($dto1, $merged->get(0));
        $this->assertSame($dto2, $merged->get(1));
        $this->assertSame($dto3, $merged->get(2));
    }

    public function testMergeDifferentCollectionType()
    {
        $collection1 = static::createLazyCollection(5);
        $collection2 = new Collection(MockCustomSerializationDto::class);

        $this->expectException(IncompatibleCollectionException::class);

        $collection1->merge($collection2);
    }

    public function testDiff()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
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
        $collection1 = static::createLazyCollectionFromEntities([$dto]);
        $collection2 = new Collection(MockCustomSerializationDto::class);

        $diff = $collection1->diff($collection2);

        $this->assertSame($collection1, $diff);
    }

    public function testIntersect()
    {
        $dto1 = (new MockDto())->setName('Test');
        $dto2 = (new MockDto())->setName('Test 2');
        $dto3 = (new MockDto())->setName('Test');

        $collection1 = static::createLazyCollectionFromEntities([
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

        $collection = static::createLazyCollectionFromEntities([
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

        $collection1 = static::createLazyCollectionFromEntities([
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
        $collection1 = static::createLazyCollectionFromEntities([$dto]);
        $collection2 = new Collection(MockCustomSerializationDto::class);

        $intersect = $collection1->intersect($collection2);

        $this->assertInstanceOf(Collection::class, $intersect);
        $this->assertNotSame($collection1, $intersect);
        $this->assertCount(0, $intersect);
    }

    public function testIteration()
    {
        $collection = static::createLazyCollection(5);

        foreach ($collection as $index => $dto) {
            $this->assertInstanceOf(MockDto::class, $dto);
            $this->assertSame($collection->get($index), $dto);
        }

        // Ensure can reiterate over generator

        foreach ($collection as $index => $dto) {
            $this->assertInstanceOf(MockDto::class, $dto);
            $this->assertSame($collection->get($index), $dto);
        }
    }

    public function testAddEntity()
    {
        $dto = new MockDto();
        $emptyCollection = static::createLazyCollection(0);
        $collection = $emptyCollection->add($dto);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(1, $collection->count());
    }

    public function testAddEntityAlreadyInCollection()
    {
        $dto = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$dto]);

        $newCollection = $collection->add($dto);

        $this->assertSame($collection, $newCollection);
    }

    public function testAddMultipleEntities()
    {
        $emptyCollection = static::createLazyCollection(0);
        $collection = $emptyCollection->add(new MockDto(), new MockDto());

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotSame($emptyCollection, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testAddInvalidEntity()
    {
        $collection = static::createLazyCollection(5);

        $this->expectException(IncompatibleDtoException::class);

        $collection->add(new MockBasicDto());
    }

    public function testRemoveEntity()
    {
        $dto = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$dto]);

        $newCollection = $collection->remove($dto);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveMultipleEntities()
    {
        $dto1 = new MockDto();
        $dto2 = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$dto1, $dto2]);

        $newCollection = $collection->remove($dto1, $dto2);

        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertEquals(0, $newCollection->count());
    }

    public function testRemoveEntityNotInCollection()
    {
        $dto = new MockDto();
        $collection = static::createLazyCollection(5);

        $this->expectException(NotInCollectionException::class);

        $collection->remove($dto);
    }

    public function testReplaceEntity()
    {
        $oldEntity = new MockDto(['name' => 'Test']);
        $newEntity = new MockDto(['name' => 'Test 2']);

        $collection = static::createLazyCollectionFromEntities([new MockDto(), $oldEntity]);

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
        $collection = static::createLazyCollectionFromEntities([$newEntity]);

        $this->expectException(NotInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testReplaceEntityWithInvalidType()
    {
        $validEntity = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$validEntity]);

        $this->expectException(IncompatibleDtoException::class);

        $collection->replace($validEntity, new MockBasicDto());
    }

    public function testReplaceEntityWithEntityAlreadyInCollection()
    {
        $oldEntity = new MockDto(['name' => 'Test']);
        $newEntity = new MockDto(['name' => 'Test 2']);

        $collection = static::createLazyCollectionFromEntities([$oldEntity, $newEntity]);

        $this->expectException(AlreadyInCollectionException::class);

        $collection->replace($oldEntity, $newEntity);
    }

    public function testGet()
    {
        $dto = new MockDto();
        $collection = static::createLazyCollectionFromEntities([$dto]);

        $this->assertSame($dto, $collection->get(0));
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

        $mapped = $collection->map(static function (MockDto $dto): MockDto {
            return $dto->setTitle('Map');
        });

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());

        foreach ($mapped as $i => $dto) {
            $this->assertInstanceOf(MockDto::class, $dto);
            $this->assertEquals($collection->get($i)->getName(), $dto->getName());
            $this->assertEquals('Map', $dto->getTitle());
        }
    }

    public function testMapToNewEntityType()
    {
        $collection = static::createLazyCollection(5);

        $mapped = $collection->map(static function (MockDto $dto): MockCustomSerializationDto {
            return (new MockCustomSerializationDto())->setName($dto->getName());
        }, MockCustomSerializationDto::class);

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertNotSame($collection, $mapped);
        $this->assertEquals($collection->count(), $mapped->count());
        $this->assertEquals(MockCustomSerializationDto::class, $mapped->getDtoType());

        foreach ($mapped as $i => $dto) {
            $this->assertInstanceOf(MockCustomSerializationDto::class, $dto);
            $this->assertEquals($collection->get($i)->getName(), $dto->getName());
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
            MockDto::class,
            new Factory(),
            static function () use ($entities) {
                foreach ($entities as $i => $dto) {
                    yield $i => $dto;
                }
            },
            $knownSize ? count($entities) : null,
        );
    }

    private static function createLazyCollection(int $size, bool $knownSize = true): LazyCollection
    {
        $entities = [];

        for ($i = 0; $i < $size; $i++) {
            $entities[] = (new MockDto())->setName(sprintf('Item %s', $size + 1));
        }

        return static::createLazyCollectionFromEntities($entities, $knownSize);
    }
}
