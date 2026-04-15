<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\StringMaxLength;
use Exterrestris\DtoFramework\Validation\Rule\StringMaxLengthPreference;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(StringMaxLengthPreference::class)]
#[UsesClass(StringMaxLength::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class StringMaxLengthPreferenceTest extends PropertyPreferenceValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [5, 10],
                null,
            ],
            [
                [0, 10],
                '',
            ],
            [
                [5, 10],
                'dsgsr',
            ],
            [
                [5, 10],
                'dsgsrxfhrt',
            ],
            [
                [5, 10],
                'as',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [2, 10],
                0,
            ],
            [
                [0, 10],
                false,
            ],
            [
                [5, 10],
                'fdghgdhdhhsges',
            ],
        ];
    }

    public static function valuePassesPreferenceValidationProvider(): array
    {
        return [
            [
                [5, 10],
                null,
            ],
            [
                [0, 10],
                '',
            ],
            [
                [5, 10],
                'dsgsr',
            ],
            [
                [5, 10],
                'as',
            ],
        ];
    }

    public static function valueFailsPreferenceValidationProvider(): array
    {
        return [
            [
                [2, 10],
                0,
            ],
            [
                [0, 10],
                false,
            ],
            [
                [5, 10],
                'dsgsrxfht',
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [-1, -1],
                '',
            ],
        ];
    }

    public static function strictnessProvider(): array
    {
        return [
            [
                [5, 10],
                'aaa',
                'aaasss',
            ]
        ];
    }
}
