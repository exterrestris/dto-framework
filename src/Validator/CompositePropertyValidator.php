<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\CompositePropertyValidatorException;

interface CompositePropertyValidator extends PropertyValidator
{
    /**
     * @return PropertyValidator[]
     */
    public function getValidators(): array;

    /**
     * @param mixed $value
     * @param DtoInterface $dto
     * @param string $dtoProperty
     * @return void
     * @throws CompositePropertyValidatorException
     */
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void;
}
