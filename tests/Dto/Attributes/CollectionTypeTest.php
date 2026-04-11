<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto\Attributes;

use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockBasicDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;
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
            [
                [ProcessableDtoInterface::class],
                new Collection(MockDto::class),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(MockDto::class, [new MockDto()]),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(MockDtoInterface::class),
            ],
            [
                [ProcessableDtoInterface::class],
                new Collection(MockDtoInterface::class, [new MockDto()]),
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new CollectionType(...$params);
    }
}
