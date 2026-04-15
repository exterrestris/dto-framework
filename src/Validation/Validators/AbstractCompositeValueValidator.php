<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Validation\CompositeValueValidator;
use Exterrestris\DtoFramework\Validation\Traits\CompositeValueValidatorTrait;

abstract readonly class AbstractCompositeValueValidator implements CompositeValueValidator
{
    use CompositeValueValidatorTrait;
}
