<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\StringMaxLength;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(StringMaxLength::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class StringMaxLengthTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [5],
                null,
            ],
            [
                [0],
                '',
            ],
            [
                [5],
                'dsgsr',
            ],
            [
                [5],
                'as',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [2],
                0,
            ],
            [
                [0],
                false,
            ],
            [
                [5],
                'fdghgdhdhh',
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [-1],
                '',
            ],
        ];
    }
}
