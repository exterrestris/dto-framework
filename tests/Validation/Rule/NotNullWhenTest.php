<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rule\NotNullWhen;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NotNullWhen::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
class NotNullWhenTest extends DependentPropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfNull],
                null,
                '',
            ],
            [
                [NullDependentValue::PassIfNull],
                null,
                0,
            ],
            [
                [NullDependentValue::PassIfNull],
                null,
                false,
            ],
            [
                [NullDependentValue::PassIfNull],
                null,
                'string',
            ],
            [
                [NullDependentValue::PassIfNull],
                'value2',
                '',
            ],
            [
                [NullDependentValue::PassIfNull],
                'value2',
                0,
            ],
            [
                [NullDependentValue::PassIfNull],
                'value2',
                false,
            ],
            [
                [NullDependentValue::PassIfNull],
                'value3',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                'value3',
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value1',
                '',
            ],
            [
                [NullDependentValue::FailIfNull],
                'value1',
                0,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value1',
                false,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value3',
                null,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                'value1',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                'value2',
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                0,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                false,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                'string',
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                'value1',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                '',
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value2',
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                '',
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                0,
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                false,
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                'string',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidatorInterface
    {
        return new NotNullWhen('dependsOn', ['value1', 'value2'], ...$params);
    }
}
