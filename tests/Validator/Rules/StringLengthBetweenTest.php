<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringLengthBetween;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringLengthBetween::class)]
class StringLengthBetweenTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [5, 10],
                null,
            ],
            [
                [5, 10],
                'abcde',
            ],
            [
                [5, 10],
                'abcdefg',
            ],
            [
                [5, 10],
                'abcdefghij',
            ],
            [
                [0, 10],
                'dsgsrg',
            ],
            [
                [0, 10],
                '',
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
                'abcd',
            ],
            [
                [5, 10],
                'abcdefghijk',
            ],
            [
                [0, 10],
                'asdsddfgsrg',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new StringLengthBetween(...$params);
    }
}
