<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\StringLengthBetween;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(StringLengthBetween::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class StringLengthBetweenTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [5, 10],
                null,
            ],
            [
                [5, 10],
                'abcde',
            ],
            [
                [5, 10],
                'abcdefg',
            ],
            [
                [5, 10],
                'abcdefghij',
            ],
            [
                [0, 10],
                'dsgsrg',
            ],
            [
                [0, 10],
                '',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [2, 10],
                0,
            ],
            [
                [0, 10],
                false,
            ],
            [
                [5, 10],
                'abcd',
            ],
            [
                [5, 10],
                'abcdefghijk',
            ],
            [
                [0, 10],
                'asdsddfgsrg',
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new StringLengthBetween(...$params);
    }
}
