<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;

interface PropertyValidator
{
    /**
     * @param mixed $value
     * @param DtoInterface $dto
     * @param string $dtoProperty
     * @return void
     * @throws PropertyValidatorException
     */
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void;
}
