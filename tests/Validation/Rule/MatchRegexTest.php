<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\MatchRegex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(MatchRegex::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class MatchRegexTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
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

    public static function valueFailsValidationProvider(): array
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

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [
                    ''
                ],
                '',
            ],
            [
                [
                    '/^[^<>]*$'
                ],
                '',
            ],
            [
                [
                    '/^[^<>*$/'
                ],
                '',
            ],
        ];
    }
}
