<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Throwable;

class RequireOneOfValidationException extends PropertyValidationException
{
    /**
     * @param PropertyValidator $validator
     * @param string $property
     * @param string[] $missingProperties
     * @param Throwable|null $previous
     */
    public function __construct(
        PropertyValidator $validator,
        string $property,
        private readonly array $missingProperties,
        ?Throwable $previous = null,
    ) {
        parent::__construct($validator, $property, sprintf(
            'At least one of "%s" or "%s" is required',
            implode('", "', array_slice($missingProperties, 0, -1)),
            $this->missingProperties[array_key_last($this->missingProperties)],
        ), $previous);
    }

    /**
     * @return string[]
     */
    public function getMissingProperties(): array
    {
        return $this->missingProperties;
    }
}
