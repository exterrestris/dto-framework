<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rules\NumberGreaterThan;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(NumberGreaterThan::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class NumberGreaterThanTest extends PropertyValueValidatorTestCase
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

    public static function valueFailsValidationProvider(): array
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
}
