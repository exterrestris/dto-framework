<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Validator\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Rule\AbstractCompositeRule;
use Exterrestris\DtoFramework\Validation\Rule\MatchRegex;
use Exterrestris\DtoFramework\Validation\Rule\StringMaxLength;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Title extends AbstractCompositeRule
{
    public function getValidators(): array
    {
        return [
            new StringMaxLength(5),
            new MatchRegex('/^[A-Za-z]*$/', 'Value must contain only letters'),
        ];
    }
}
