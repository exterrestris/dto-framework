<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\StringMinLength;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(StringMinLength::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class StringMinLengthTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
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
                [0],
                'dsgsrg',
            ],
            [
                [5],
                'asdsd',
            ],
            [
                [5],
                'asdsddfgsrg',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
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
                [10],
                'asdsd',
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new StringMinLength(...$params);
    }
}
