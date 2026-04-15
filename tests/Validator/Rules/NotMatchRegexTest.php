<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NotMatchRegex;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(NotMatchRegex::class)]
#[Group('validation')]
#[Group('validator-rules')]
class NotMatchRegexTest extends PropertyValueValidatorTestCase
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
                '<string>',
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
                'string',
            ],
            [
                [
                    '/^[^<>]*$/',
                    'Custom message'
                ],
                'string',
                'Custom message'
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new NotMatchRegex(...$params);
    }
}
