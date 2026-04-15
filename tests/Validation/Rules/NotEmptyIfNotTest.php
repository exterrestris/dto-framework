<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rules\NotEmptyIfNot;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NotEmptyIfNot::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
class NotEmptyIfNotTest extends DependentPropertyValidatorTestCase
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
                'value',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                'value',
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
                'value',
                null,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                'value2',
                null,
            ],
            [
                [NullDependentValue::PassIfNull],
                'value2',
                '',
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
                'value2',
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                'value2',
                '',
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
                'value2',
                '',
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

    protected function getValidator(array $params): PropertyValidator
    {
        return new NotEmptyIfNot('dependsOn', 'value', ...$params);
    }
}
