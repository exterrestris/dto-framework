<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockHierarchicalDto;
use Exterrestris\DtoFramework\Validation\Exception\InvalidCollectionException;
use Exterrestris\DtoFramework\Validation\Exception\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\EmptyValueTrait;
use Exterrestris\DtoFramework\Validation\Rule\NotEmpty;
use Exterrestris\DtoFramework\Validation\Rule\NoValidate;
use Exterrestris\DtoFramework\Validation\Rule\ValidCollection;
use Exterrestris\DtoFramework\Validation\Rule\ValidDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(ValidDto::class)]
#[UsesClass(CollectionType::class)]
#[UsesClass(NotEmpty::class)]
#[UsesClass(EmptyValueTrait::class)]
#[UsesClass(ValidCollection::class)]
#[UsesClass(InvalidCollectionException::class)]
#[UsesClass(InvalidDtoException::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueValidationException::class)]
class ValidDtoTest extends PropertyValueValidatorTestCase
{
    protected static function createDtoFromValue(mixed $value): DtoInterface
    {
        return new MockHierarchicalDto([
            'name' => 'Jack Doe',
            'parent' => $value,
        ]);
    }

    protected static function getDtoPropertyToValidate(): string
    {
        return 'parent';
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
                new MockHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => null,
                ]),
            ],
            [
                [],
                new MockHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => new MockHierarchicalDto([
                        'name' => 'Jane Doe',
                    ]),
                ]),
            ],
            [
                [],
                new #[NoValidate] class([]) extends MockHierarchicalDto {},
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                0,
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Dto\DtoInterface',
            ],
            [
                [],
                '',
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Dto\DtoInterface',
            ],
            [
                [],
                [],
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Dto\DtoInterface',
            ],
            [
                [],
                new MockHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => new MockHierarchicalDto([
                        'name' => '',
                    ]),
                ]),
                <<<'MESSAGE'
                1 MockHierarchicalDto property is invalid
                - parent: 1 MockHierarchicalDto property is invalid
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
                    new MockHierarchicalDto([
                        'name' => 'John Doe',
                        'parent' => new MockHierarchicalDto([
                            'name' => '',
                        ]),
                    ])
                ),
                static::getDtoPropertyToValidate(),
                <<<'MESSAGE'
                1 MockHierarchicalDto property is invalid
                - parent: 1 MockHierarchicalDto property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ]
        ];
    }
}
