<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\DateOnOrAfterProperty;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(DateOnOrAfterProperty::class)]
#[Group('validation')]
#[Group('validator-rules')]
class DateOnOrAfterPropertyTest extends DependentPropertyValidatorTestCase
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
                new DateTimeImmutable('2024-10-10'),
                null,
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-10'),
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10'),
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-10'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10'),
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-10'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-11'),
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-09'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-09'),
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::FailIfNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-11'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10'),
                new DateTimeImmutable('2024-10-09'),
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new DateOnOrAfterProperty('dependsOn', ...$params);
    }
}
