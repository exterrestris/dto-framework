<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Iso3166Alpha2CountryCode;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(Iso3166Alpha2CountryCode::class)]
#[Group('validation')]
#[Group('validator-rules')]
class Iso3166Alpha2CountryCodeTest extends PropertyValueValidatorTestCase
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

    public static function valueFailsValidationProvider(): array
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new Iso3166Alpha2CountryCode();
    }
}
