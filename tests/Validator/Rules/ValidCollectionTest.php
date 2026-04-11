<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockHierarchicalDto;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidCollectionDtoException;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidCollectionException;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NotEmpty;
use Exterrestris\DtoFramework\Validator\Rules\Traits\EmptyValueTrait;
use Exterrestris\DtoFramework\Validator\Rules\ValidCollection;
use Exterrestris\DtoFramework\Validator\Rules\ValidDto;
use Exterrestris\DtoFramework\Validator\ValueValidator;
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
        $value = (new Collection(MockHierarchicalDto::class))->add(
            new MockHierarchicalDto([
                'name' => 'John Doe',
                'parent' => new MockHierarchicalDto([
                    'name' => '',
                ]),
            ]),
        );
        $dto = static::createDtoFromValue($value);

        return [
            [
                [],
                (new \ReflectionObject($dto))->getProperty(static::getDtoPropertyToValidate()),
                $dto,
                <<<'MESSAGE'
                MockHierarchicalDto 0: 1 property is invalid
                - parent: 1 property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ]
        ];
    }

    protected function getValidator(array $params): PropertyValidator&ValueValidator
    {
        return new ValidCollection();
    }
}
