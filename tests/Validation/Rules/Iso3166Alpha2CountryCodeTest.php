<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\Iso3166Alpha2CountryCode;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(Iso3166Alpha2CountryCode::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
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
