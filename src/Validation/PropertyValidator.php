<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorException;
use ReflectionProperty;

interface PropertyValidator
{
    /**
     * @param ReflectionProperty $dtoProperty
     * @param DtoInterface $forDto
     * @return void
     * @throws PropertyValidatorException
     */
    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void;
}
