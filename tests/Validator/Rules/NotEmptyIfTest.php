<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\NotEmptyIf;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(NotEmptyIf::class)]
#[Group('validation')]
#[Group('validator-rules')]
class NotEmptyIfTest extends DependentPropertyValidatorTestCase
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
                [NullDependentValue::PassIfNull],
                'value',
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
                'value',
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
        return new NotEmptyIf('dependsOn', 'value', ...$params);
    }
}
