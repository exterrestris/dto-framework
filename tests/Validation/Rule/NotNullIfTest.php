<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rule\NotNullIf;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NotNullIf::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
class NotNullIfTest extends DependentPropertyValidatorTestCase
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
                'value',
                '',
            ],
            [
                [NullDependentValue::PassIfNull],
                'value',
                0,
            ],
            [
                [NullDependentValue::PassIfNull],
                'value',
                false,
            ],
            [
                [NullDependentValue::PassIfNull],
                'value2',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                'value2',
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value',
                '',
            ],
            [
                [NullDependentValue::FailIfNull],
                'value',
                0,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value',
                false,
            ],
            [
                [NullDependentValue::FailIfNull],
                'value2',
                null,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                'value',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                'value',
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
                'value',
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
                'value',
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
        return new NotNullIf('dependsOn', 'value', ...$params);
    }
}
