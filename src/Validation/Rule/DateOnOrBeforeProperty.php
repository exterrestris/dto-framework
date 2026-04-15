<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use DateTimeInterface;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class DateOnOrBeforeProperty extends AbstractDependentPropertyRule
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

        if ($value > $dependentValue) {
            throw new ValueException(sprintf('Value must be before or equal to %s', $this->property));
        }
    }
}
