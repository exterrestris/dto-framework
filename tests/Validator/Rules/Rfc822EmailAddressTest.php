<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Rfc822EmailAddress;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Rfc822EmailAddress::class)]
class Rfc822EmailAddressTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
            [
                [],
                'test@test.com',
            ],
            [
                [],
                'test+testing@test.com',
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [],
                0,
            ],
            [
                [],
                '',
            ],
            [
                [0],
                'af',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new Rfc822EmailAddress();
    }
}
