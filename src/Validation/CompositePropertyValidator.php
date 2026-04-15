<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\CompositePropertyValidatorException;
use ReflectionProperty;

interface CompositePropertyValidator extends PropertyValidator
{
    /**
     * @return PropertyValidator[]
     */
    public function getValidators(): array;


    /**
     * @param ReflectionProperty $dtoProperty
     * @param DtoInterface $forDto
     * @return void
     * @throws CompositePropertyValidatorException
     */
    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void;
}
