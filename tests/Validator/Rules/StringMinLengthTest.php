<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringMinLength;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringMinLength::class)]
class StringMinLengthTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
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
                [0],
                'dsgsrg',
            ],
            [
                [5],
                'asdsd',
            ],
            [
                [5],
                'asdsddfgsrg',
            ],
        ];
    }

    public static function failValidationProvider(): array
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
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new StringMinLength(...$params);
    }
}
