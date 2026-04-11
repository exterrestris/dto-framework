<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NumberBetween;
use Exterrestris\DtoFramework\Validator\ValueValidator;
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new NumberBetween(...$params);
    }
}
