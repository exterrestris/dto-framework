<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Traits\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsInList implements PropertyValidator
{
    use CompileArrayValuesTrait;

    public function __construct(
        private array $allowedValues,
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            return;
        }

        if (!in_array($value, $this->allowedValues)) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be %s', $this->compileValues($this->allowedValues)),
            );
        }
    }
}
