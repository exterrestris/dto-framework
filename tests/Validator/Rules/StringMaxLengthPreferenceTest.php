<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PreferenceValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringMaxLengthPreference;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringMaxLengthPreference::class)]
class StringMaxLengthPreferenceTest extends PreferenceValidatorTestCase
{
    public static function passValidationProvider(): array
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

    public static function failValidationProvider(): array
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

    public static function passPreferenceValidationProvider(): array
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

    public static function failPreferenceValidationProvider(): array
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
                'dsgsrxfhrt',
            ],
        ];
    }

    protected function getValidator(array $params): PreferenceValidator
    {
        return new StringMaxLengthPreference(...$params);
    }
}
