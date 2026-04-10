<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NumberGreaterThan;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NumberGreaterThan::class)]
class NumberGreaterThanTest extends PropertyValidatorTestCase
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
                1,
            ],
            [
                [-2],
                0,
            ],
            [
                [-2.5],
                -2,
            ],
            [
                [3],
                3.5,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [0],
                0,
            ],
            [
                [0],
                -1,
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
        return new NumberGreaterThan(...$params);
    }
}
