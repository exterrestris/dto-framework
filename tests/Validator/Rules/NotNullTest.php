<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NotNull;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(NotNull::class)]
#[Group('validation')]
#[Group('validator-rules')]
class NotNullTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [],
                '',
            ],
            [
                [],
                0,
            ],
            [
                [],
                'string',
            ],
            [
                [],
                false,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new NotNull();
    }
}
