<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NumberGreaterThanOrEqualTo;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(NumberGreaterThanOrEqualTo::class)]
#[Group('validation')]
#[Group('validator-rules')]
class NumberGreaterThanOrEqualToTest extends PropertyValueValidatorTestCase
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new NumberGreaterThanOrEqualTo(...$params);
    }
}
