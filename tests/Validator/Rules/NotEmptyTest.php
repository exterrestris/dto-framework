<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NotEmpty;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NotEmpty::class)]
class NotEmptyTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [],
                0,
            ],
            [
                [],
                'string',
            ],
            [
                [],
                false,
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
            [
                [],
                '',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new NotEmpty();
    }
}
