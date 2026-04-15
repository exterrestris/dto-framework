<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractCompositePropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\MatchRegex;
use Exterrestris\DtoFramework\Validator\Rules\StringMaxLength;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Title extends AbstractCompositePropertyValidator
{
    public function getValidators(): array
    {
        return [
            new StringMaxLength(5),
            new MatchRegex('/^[A-Za-z]*$/', 'Value must contain only letters'),
        ];
    }
}
