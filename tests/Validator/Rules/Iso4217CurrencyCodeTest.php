<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Iso4217CurrencyCode;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Iso4217CurrencyCode::class)]
class Iso4217CurrencyCodeTest extends PropertyValidatorTestCase
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
                'GBP',
            ],
            [
                [],
                'gbp',
            ],
            [
                [],
                'EUR',
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [],
                '£',
            ],
            [
                [],
                'US$',
            ],
            [
                [],
                '',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new Iso4217CurrencyCode();
    }
}
