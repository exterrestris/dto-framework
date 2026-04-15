<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockHierarchicalDto;
use Exterrestris\DtoFramework\Validation\Exception\InvalidCollectionDtoException;
use Exterrestris\DtoFramework\Validation\Exception\InvalidCollectionException;
use Exterrestris\DtoFramework\Validation\Exception\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\EmptyValueTrait;
use Exterrestris\DtoFramework\Validation\Rule\NotEmpty;
use Exterrestris\DtoFramework\Validation\Rule\ValidCollection;
use Exterrestris\DtoFramework\Validation\Rule\ValidDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(ValidCollection::class)]
#[UsesClass(Collection::class)]
#[UsesClass(InvalidCollectionException::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(CollectionType::class)]
#[UsesClass(NotEmpty::class)]
#[UsesClass(EmptyValueTrait::class)]
#[UsesClass(ValidDto::class)]
#[UsesClass(InvalidCollectionDtoException::class)]
#[UsesClass(InvalidDtoException::class)]
class ValidCollectionTest extends PropertyValueValidatorTestCase
{
    protected static function createDtoFromValue(mixed $value): DtoInterface
    {
        return new MockHierarchicalDto([
            'name' => 'Jack Doe',
            'children' => $value,
        ]);
    }

    protected static function getDtoPropertyToValidate(): string
    {
        return 'children';
    }

    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
            [
                [],
                new Collection(MockHierarchicalDto::class),
            ],
            [
                [],
                (new Collection(MockHierarchicalDto::class))->add(
                    new MockHierarchicalDto([
                        'name' => 'John Doe',
                        'children' => null,
                    ]),
                ),
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                0,
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                [],
                '',
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                [],
                [],
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                [],
                (new Collection(MockHierarchicalDto::class))->add(
                    new MockHierarchicalDto([
                        'name' => 'John Doe',
                        'parent' => new MockHierarchicalDto([
                            'name' => '',
                        ]),
                    ]),
                ),
                <<<'MESSAGE'
                MockHierarchicalDto 0: 1 property is invalid
                - parent: 1 property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ],
        ];
    }

    public static function propertyFailsValidationProvider(): array
    {
        return [
            [
                [],
                static::createDtoFromValue(
                    (new Collection(MockHierarchicalDto::class))->add(
                        new MockHierarchicalDto([
                            'name' => 'John Doe',
                            'parent' => new MockHierarchicalDto([
                                'name' => '',
                            ]),
                        ]),
                    )
                ),
                static::getDtoPropertyToValidate(),
                <<<'MESSAGE'
                MockHierarchicalDto 0: 1 property is invalid
                - parent: 1 property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ]
        ];
    }
}
