<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule\Exception;

use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Throwable;

class RequireOneOfValidationException extends PropertyValidationException
{
    /**
     * @param PropertyValidatorInterface $validator
     * @param string $property
     * @param string[] $missingProperties
     * @param Throwable|null $previous
     */
    public function __construct(
        PropertyValidatorInterface $validator,
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
