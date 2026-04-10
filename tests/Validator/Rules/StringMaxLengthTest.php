<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringMaxLength;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringMaxLength::class)]
class StringMaxLengthTest extends PropertyValidatorTestCase
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
                [5],
                'dsgsr',
            ],
            [
                [5],
                'as',
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
                'fdghgdhdhh',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new StringMaxLength(...$params);
    }
}
