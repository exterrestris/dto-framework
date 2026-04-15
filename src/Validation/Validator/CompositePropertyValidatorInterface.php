<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\CompositePropertyValidatorException;
use ReflectionProperty;

interface CompositePropertyValidatorInterface extends PropertyValidatorInterface
{
    /**
     * @return PropertyValidatorInterface[]
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
