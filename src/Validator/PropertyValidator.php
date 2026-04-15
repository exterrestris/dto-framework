<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
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
