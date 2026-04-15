<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Validation\CompositePropertyValidator;
use Exterrestris\DtoFramework\Validation\Traits\CompositePropertyValidatorTrait;

abstract readonly class AbstractCompositePropertyValidator implements CompositePropertyValidator
{
    use CompositePropertyValidatorTrait;
}
