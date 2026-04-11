<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\IsInList;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(IsInList::class)]
#[Group('validation')]
#[Group('validator-rules')]
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
