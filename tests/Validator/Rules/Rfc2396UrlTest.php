<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Rfc2396Url;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Rfc2396Url::class)]
class Rfc2396UrlTest extends PropertyValidatorTestCase
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
                'https://www.brandalley.co.uk/',
            ],
            [
                [],
                'https://www.brandalley.co.uk/path/to/file',
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
                [],
                'af',
            ],
            [
                [],
                'https://www.brandalley.co.uk',
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new Rfc2396Url();
    }
}
