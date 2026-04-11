<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\DateAfterProperty;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(DateAfterProperty::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
class DateAfterPropertyTest extends DependentPropertyValidatorTestCase
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
                new DateTimeImmutable('2024-10-10'),
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
                new DateTimeImmutable('2024-10-10'),
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
                new DateTimeImmutable('2024-10-10'),
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
        return new DateAfterProperty('dependsOn', ...$params);
    }
}
