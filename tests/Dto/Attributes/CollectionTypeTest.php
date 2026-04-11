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
use Exterrestris\DtoFramework\Tests\Validator\Rules\PropertyValueValidatorTestCase;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(CollectionType::class)]
#[Group('validation')]
#[Group('validator-rules')]
class CollectionTypeTest extends PropertyValueValidatorTestCase
{

    public function testGetEntityType()
    {
        $attribute = new CollectionType(TestEntity::class);

        $this->assertEquals(TestEntity::class, $attribute->getDtoType());
    }

    public static function valuePassesValidationProvider(): array
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

    public static function valueFailsValidationProvider(): array
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new CollectionType(...$params);
    }
}
