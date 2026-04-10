<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Attributes;

use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\TestDto;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntity;
use Exterrestris\DtoFramework\Tests\Mocks\TestEntityInterface;
use Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;
use Exterrestris\DtoFramework\Tests\Validator\Rules\PropertyValidatorTestCase;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CollectionType::class)]
class CollectionTypeTest extends PropertyValidatorTestCase
{

    public function testGetEntityType()
    {
        $attribute = new CollectionType(TestEntity::class);

        $this->assertEquals(TestEntity::class, $attribute->getDtoType());
    }

    /**
     * @inheritDoc
     */
    public static function passValidationProvider(): array
    {
        return [
            [
                [TestEntity::class],
                null,
            ],
            [
                [TestEntity::class],
                new Collection(TestEntity::class),
            ],
            [
                [TestEntity::class],
                new Collection(TestEntity::class, [new TestEntity()]),
            ],
            [
                [TestEntityInterface::class],
                new Collection(TestEntity::class),
            ],
            [
                [TestEntityInterface::class],
                new Collection(TestEntity::class, [new TestEntity()]),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(TestEntity::class),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(TestEntity::class, [new TestEntity()]),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(TestEntityInterface::class),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(TestEntityInterface::class, [new TestEntity()]),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function failValidationProvider(): array
    {
        return [
            [
                [TestDto::class],
                new Collection(TestEntity::class, [new TestEntity()]),
            ],
            [
                [TestEntity::class],
                new Collection(CustomSerializationEntity::class),
            ],
            [
                [TestEntityInterface::class],
                new Collection(CustomSerializationEntity::class),
            ],
            [
                [TestEntity::class],
                [],
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new CollectionType(...$params);
    }
}
