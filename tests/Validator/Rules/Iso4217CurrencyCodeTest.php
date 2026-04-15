<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Iso4217CurrencyCode;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(Iso4217CurrencyCode::class)]
#[Group('validation')]
#[Group('validator-rules')]
class Iso4217CurrencyCodeTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
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

    public static function valueFailsValidationProvider(): array
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new Iso4217CurrencyCode();
    }
}
