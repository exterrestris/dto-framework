<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Validation\CompositePropertyValidator;
use Exterrestris\DtoFramework\Validation\CompositeValueValidator;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Traits\CompositePropertyValidatorTrait;
use Exterrestris\DtoFramework\Validation\Traits\CompositeValueValidatorTrait;
use Exterrestris\DtoFramework\Validation\ValueValidator;

abstract readonly class AbstractCompositePropertyValueValidator implements CompositeValueValidator, CompositePropertyValidator
{
    use CompositeValueValidatorTrait;
    use CompositePropertyValidatorTrait;

    /**
     * @return PropertyValidator&ValueValidator[]
     */
    abstract public function getValidators(): array;
}
