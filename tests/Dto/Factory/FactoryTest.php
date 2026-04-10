<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Factory;

use Closure;
use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\LazyCollection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\FactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\UnknownTypeException;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntityInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Factory::class)]
#[UsesClass(AbstractDto::class)]
#[UsesClass(Collection::class)]
class FactoryTest extends TestCase
{
    public static function createProvider(): array
    {
        return [
            [
                TestEntity::class,
                TestEntity::class,
            ],
            [
                TestEntityInterface::class,
                TestEntity::class,
            ],
        ];
    }

    #[DataProvider('createProvider')]
    public function testCreateDto(string $createDtoType, $expectedDtoType): void
    {
        $factory = new Factory();

        $this->assertInstanceOf($expectedDtoType, $factory->create($createDtoType));

        $entity = $factory->create($createDtoType, ['name' => 'test']);

        $this->assertInstanceOf($expectedDtoType, $entity);
        $this->assertEquals('test', $entity->getName());
    }

    public static function createThrowProvider(): array
    {
        return [
            [
                DtoInterface::class,
                InvalidTypeException::class,
            ],
            [
                Collection::class,
                InvalidTypeException::class,
            ],
            [
                CollectionInterface::class,
                InvalidTypeException::class,
            ],
            [
                'NonExistentClass',
                UnknownTypeException::class,
            ],
        ];
    }

    /**
     * @param class-string $dtoType
     * @param class-string<FactoryException> $expectedException
     * @return void
     */
    #[DataProvider('createThrowProvider')]
    public function testCreateDtoThrows(string $dtoType, string $expectedException): void
    {
        $factory = new Factory();

        try {
            $factory->create($dtoType);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FactoryException::class, $exception);
            $this->assertInstanceOf($expectedException, $exception);
            return;
        }

        $this->fail('FactoryException not thrown');
    }


    public function testCreateCollection()
    {
        $factory = new Factory();

        $collection = $factory->createCollection(TestEntity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(TestEntity::class, $collection->getDtoType());
        $this->assertTrue($collection->isEmpty());
    }

    public static function createCollectionForProvider(): array
    {
        return [
            [
                (new TestEntity())->setName('test')->setTitle('test'),
                (new TestEntity())->setName('test'),
                TestEntityInterface::class,
            ],
        ];
    }

    #[DataProvider('createCollectionForProvider')]
    public function testCreateCollectionFor(DtoInterface $entity1, DtoInterface $entity2, string $expectedType)
    {
        $factory = new Factory();

        $collection = $factory->createCollectionFor($entity1, $entity2);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals($expectedType, $collection->getDtoType());
        $this->assertFalse($collection->isEmpty());
        $this->assertSame($entity1, $collection->get(0));
        $this->assertSame($entity2, $collection->get(1));
    }

    public static function createLazyCollectionProvider(): array
    {
        $closure = function () {
            $entities = [new TestEntity(), new TestEntity()];

            foreach ($entities as $i => $entity) {
                yield $i => $entity;
            }
        };

        return [
            [
                $closure,
                2,
                true,
                2,
            ],
            [
                $closure,
                0,
                true,
                0,
            ],
            [
                $closure,
                null,
                false,
                2,
            ],
        ];
    }

    #[DataProvider('createLazyCollectionProvider')]
    public function testCreateLazyCollection(
        Closure $generatorFn,
        ?int $entityCount,
        bool $expectedCountKnown,
        int $expectedCount
    ) {
        $factory = new Factory();

        $collection = $factory->createLazyCollection(TestEntity::class, $generatorFn, $entityCount);

        $this->assertInstanceOf(LazyCollection::class, $collection);
        $this->assertSame(TestEntity::class, $collection->getDtoType());
        $this->assertEquals($expectedCountKnown, $collection->isCountKnown());
        $this->assertEquals($expectedCount, $collection->count());
    }
}
