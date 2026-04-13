<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use DateTimeInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractDependentPropertyPropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class DateOnOrAfterProperty extends AbstractDependentPropertyPropertyValidator
{
    protected function validateValueAgainst(mixed $value, mixed $dependentValue): void
    {
        if ($value === null || $dependentValue === null) {
            return;
        }

        if (!$value instanceof DateTimeInterface) {
            throw new ValueException('Value is not a date');
        }

        if (!$dependentValue instanceof DateTimeInterface) {
            throw new ValueException('Dependent value is not a date');
        }

        if ($value < $dependentValue) {
            throw new ValueException(sprintf('Value must be equal to or after %s', $this->property));
        }
    }
}
