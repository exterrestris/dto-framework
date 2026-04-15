<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\NumberLessThanOrEqualTo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NumberLessThanOrEqualTo::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class NumberLessThanOrEqualToTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
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

    public static function valueFailsValidationProvider(): array
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
}
