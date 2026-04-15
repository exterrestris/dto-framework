<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;

/**
 * @method PropertyValidatorException getPrevious()
 */
class InvalidDtoPropertyException extends DtoPropertyValidationException
{
    public function __construct(
        DtoInterface $dto,
        string $property,
        PropertyValidatorException $previous,
        ?string $message = null,
    )
    {
        parent::__construct(
            $dto,
            $property,
            $message ?? sprintf('Property "%s" is invalid', $property),
            $previous
        );
    }

    public function getValidator(): PropertyValidatorInterface
    {
        return $this->getPrevious()->getValidator();
    }

    public static function from(PropertyValidatorException $e, DtoInterface $forDto, string $forProperty): self
    {
        return new self($forDto, $forProperty, $e, $e->getMessage());
    }
}
