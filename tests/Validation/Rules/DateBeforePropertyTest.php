<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rules\DateBeforeProperty;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(DateBeforeProperty::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
class DateBeforePropertyTest extends DependentPropertyValidatorTestCase
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
                new DateTimeImmutable('2024-10-10 00:00:00'),
                null,
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                null,
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [NullDependentValue::PassIfNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-11 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                null,
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            [
                [NullDependentValue::PassIfValueIsNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-11 00:00:00'),
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                null,
            ],
            [
                [NullDependentValue::FailIfNull],
                null,
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::FailIfNull],
                '2024-10-10',
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            [
                [NullDependentValue::FailIfNull],
                new DateTimeImmutable('2024-10-10 00:00:00'),
                new DateTimeImmutable('2024-10-11 00:00:00'),
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new DateBeforeProperty('dependsOn', ...$params);
    }
}
