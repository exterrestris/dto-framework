<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\NumberBetween;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NumberBetween::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class NumberBetweenTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
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
                [-20, 20],
                0,
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
            [
                [10, 1],
                5,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
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
}
