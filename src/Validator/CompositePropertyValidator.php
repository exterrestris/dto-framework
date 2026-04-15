<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\CompositePropertyValidatorException;
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
