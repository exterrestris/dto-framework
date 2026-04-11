<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\StringMaxLength;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(StringMaxLength::class)]
#[Group('validation')]
#[Group('validator-rules')]
class StringMaxLengthTest extends PropertyValueValidatorTestCase
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
                'dsgsr',
            ],
            [
                [5],
                'as',
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
                [5],
                'fdghgdhdhh',
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new StringMaxLength(...$params);
    }
}
