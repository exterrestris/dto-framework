<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\MatchRegex;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MatchRegex::class)]
class MatchRegexTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                [
                    '/^[^<>]*$/',
                ],
                null,
            ],
            [
                [
                    '/^[^<>]*$/',
                ],
                'string',
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                [
                    '/^[^<>]*$/',
                ],
                'string<',
            ],
            [
                [
                    '/^[^<>]*$/',
                    'Custom message'
                ],
                'string<',
                'Custom message'
            ],
        ];
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new MatchRegex(...$params);
    }
}
