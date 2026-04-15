<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\IsInList;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(IsInList::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(PropertyValidationException::class)]
class IsInListTest extends PropertyValueValidatorTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [
                    ['A', 'B', 'C'],
                ],
                null
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'A'
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'B'
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'C'
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [
                    ['A', 'B', 'C'],
                ],
                ''
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                0
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'D'
            ],
        ];
    }

    protected function getValidator(array $params): ValueValidator&PropertyValidator
    {
        return new IsInList(...$params);
    }
}
