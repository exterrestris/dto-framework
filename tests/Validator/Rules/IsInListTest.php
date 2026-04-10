<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\IsInList;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsInList::class)]
class IsInListTest extends PropertyValidatorTestCase
{
    public static function passValidationProvider(): array
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

    public static function failValidationProvider(): array
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

    protected function getValidator(array $params): PropertyValidator
    {
        return new IsInList(...$params);
    }
}
