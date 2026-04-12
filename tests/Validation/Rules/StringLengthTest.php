<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\StringLength;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(StringLength::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class StringLengthTest extends PropertyValueValidatorTestCase
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
                [5],
                'asdsd',
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
                [4],
                '',
            ],
            [
                [5],
                'asdsddfgsrg',
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new StringLength(...$params);
    }
}
