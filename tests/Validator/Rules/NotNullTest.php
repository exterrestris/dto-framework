<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NotNull;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NotNull::class)]
class NotNullTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [],
                '',
            ],
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
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new NotNull();
    }
}
