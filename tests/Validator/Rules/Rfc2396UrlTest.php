<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Rfc2396Url;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(Rfc2396Url::class)]
#[Group('validation')]
#[Group('validator-rules')]
class Rfc2396UrlTest extends PropertyValueValidatorTestCase
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
                'https://www.brandalley.co.uk/',
            ],
            [
                [],
                'https://www.brandalley.co.uk/path/to/file',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
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

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new Rfc2396Url();
    }
}
