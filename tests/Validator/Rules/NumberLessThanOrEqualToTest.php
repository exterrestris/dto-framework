<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NumberLessThanOrEqualTo;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NumberLessThanOrEqualTo::class)]
class NumberLessThanOrEqualToTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [0],
                null,
            ],
            [
                [0],
                0,
            ],
            [
                [0],
                -1,
            ],
            [
                [-2],
                -8,
            ],
            [
                [-2.5],
                -3,
            ],
            [
                [3],
                1.5,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [0],
                1,
            ],
            [
                [0],
                '',
            ],
            [
                [0],
                'af',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new NumberLessThanOrEqualTo(...$params);
    }
}
