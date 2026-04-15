<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules\Traits;

trait EmptyValueTrait
{
    protected function isEmpty(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value)) {
            return strlen($value) === 0;
        }

        if (is_array($value)) {
            return count($value) === 0;
        }

        return false;
    }
}
