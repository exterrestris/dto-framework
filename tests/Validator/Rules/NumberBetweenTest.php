<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NumberBetween;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NumberBetween::class)]
class NumberBetweenTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [1, 20],
                null,
            ],
            [
                [1, 20],
                1,
            ],
            [
                [1, 20],
                1.0,
            ],
            [
                [1, 20],
                5,
            ],
            [
                [1, 20],
                20,
            ],
            [
                [1, 20],
                20.0,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [1, 20],
                0,
            ],
            [
                [1, 20],
                -1,
            ],
            [
                [1, 20],
                21,
            ],
            [
                [1, 20],
                '',
            ],
            [
                [1, 20],
                'af',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new NumberBetween(...$params);
    }
}
