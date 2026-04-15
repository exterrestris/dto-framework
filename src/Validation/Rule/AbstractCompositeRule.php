<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Exterrestris\DtoFramework\Validation\Validator\CompositePropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\CompositePropertyValidatorTrait;
use Exterrestris\DtoFramework\Validation\Validator\CompositeValueValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\CompositeValueValidatorTrait;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;

abstract readonly class AbstractCompositeRule implements CompositeValueValidatorInterface, CompositePropertyValidatorInterface
{
    use CompositeValueValidatorTrait;
    use CompositePropertyValidatorTrait;

    /**
     * @return PropertyValidatorInterface&ValueValidatorInterface[]
     */
    abstract public function getValidators(): array;
}
