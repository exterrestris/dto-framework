<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Metadata;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Dto\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockBasicDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDtoInterface;
use Exterrestris\DtoFramework\Tests\Validation\Rule\PropertyValueValidatorTestCase;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(CollectionType::class)]
#[UsesClass(Collection::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
#[UsesClass(ValueValidationException::class)]
class CollectionTypeTest extends PropertyValueValidatorTestCase
{

    public function testGetDtoType()
    {
        $attribute = new CollectionType(MockDto::class);

        $this->assertEquals(MockDto::class, $attribute->getDtoType());
    }

    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [MockDto::class],
                null,
            ],
            [
                [MockDto::class],
                new Collection(MockDto::class),
            ],
            [
                [MockDto::class],
                new Collection(MockDto::class, [new MockDto()]),
            ],
            [
                [MockDtoInterface::class],
                new Collection(MockDto::class),
            ],
            [
                [MockDtoInterface::class],
                new Collection(MockDto::class, [new MockDto()]),
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [MockBasicDto::class],
                new Collection(MockDto::class, [new MockDto()]),
            ],
            [
                [MockDto::class],
                new Collection(MockCustomSerializationDto::class),
            ],
            [
                [MockDtoInterface::class],
                new Collection(MockCustomSerializationDto::class),
            ],
            [
                [MockDto::class],
                [],
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [ProcessableDtoInterface::class],
                new Collection(MockDto::class),
            ]
        ];
    }
}
