<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Validators\AbstractCompositePropertyValueValidator;
use Exterrestris\DtoFramework\Validation\Rules\MatchRegex;
use Exterrestris\DtoFramework\Validation\Rules\StringMaxLength;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Title extends AbstractCompositePropertyValueValidator
{
    public function getValidators(): array
    {
        return [
            new StringMaxLength(5),
            new MatchRegex('/^[A-Za-z]*$/', 'Value must contain only letters'),
        ];
    }
}
