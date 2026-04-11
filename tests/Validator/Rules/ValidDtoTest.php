<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockHierarchicalDto;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\ValidDto;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(ValidDto::class)]
#[Group('validation')]
#[Group('validator-rules')]
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
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                0,
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
            ],
            [
                [],
                '',
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
            ],
            [
                [],
                [],
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
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

    protected function getValidator(array $params): PropertyValidator&ValueValidator
    {
        return new ValidDto();
    }
}
