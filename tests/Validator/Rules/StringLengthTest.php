<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringLength;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringLength::class)]
class StringLengthTest extends PropertyValidatorTestCase
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
                [6],
                'dsgsrg',
            ],
            [
                [5],
                'asdsd',
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
            [
                [5],
                'asdsddfgsrg',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new StringLength(...$params);
    }
}
