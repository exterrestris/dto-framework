<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\ValidCollection;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(ValidCollection::class)]
#[Group('validation')]
#[Group('validator-rules')]
class ValidCollectionTest extends PropertyValueValidatorTestCase
{
    protected static function createDtoFromValue(mixed $value): DtoInterface
    {
        return new TestHierarchicalDto([
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
                new Collection(TestHierarchicalDto::class),
            ],
            [
                [],
                (new Collection(TestHierarchicalDto::class))->add(
                    new TestHierarchicalDto([
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
                (new Collection(TestHierarchicalDto::class))->add(
                    new TestHierarchicalDto([
                        'name' => 'John Doe',
                        'parent' => new TestHierarchicalDto([
                            'name' => '',
                        ]),
                    ]),
                ),
                <<<'MESSAGE'
                Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto 0: 1 property is invalid
                - parent: 1 property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator&ValueValidator
    {
        return new ValidCollection();
    }
}
