<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorException;
use ReflectionProperty;

interface PropertyValidatorInterface
{
    /**
     * @param ReflectionProperty $dtoProperty
     * @param DtoInterface $forDto
     * @return void
     * @throws PropertyValidatorException
     * @throws ConfigurationException
     */
    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void;
}
