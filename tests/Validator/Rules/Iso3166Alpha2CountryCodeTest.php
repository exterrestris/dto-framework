<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Iso3166Alpha2CountryCode;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Iso3166Alpha2CountryCode::class)]
class Iso3166Alpha2CountryCodeTest extends PropertyValidatorTestCase
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
                'GB',
            ],
            [
                [],
                'gb',
            ],
            [
                [],
                'IE',
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [],
                'UK',
            ],
            [
                [],
                'GBR',
            ],
            [
                [],
                '',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new Iso3166Alpha2CountryCode();
    }
}
