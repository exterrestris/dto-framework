<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use ReflectionClass;
use ReflectionException;

abstract readonly class AbstractDependentPropertyPropertyValidator implements PropertyValidator
{
    public function __construct(
        protected string $property,
        protected NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfNull,
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        try {
            try {
                $reflect = new ReflectionClass($dto);
                $property = $reflect->getProperty($this->property);
                $dependentValue = $property->isInitialized($dto) ? $property->getValue($dto) : null;

                if ($dependentValue === null) {
                    $passValidation = match ($this->nullDependentValueBehaviour) {
                        NullDependentValue::PassIfNull => true,
                        NullDependentValue::PassIfValueIsNull => $value === null,
                        NullDependentValue::FailIfNull => false,
                    };

                    if ($passValidation) {
                        return;
                    }

                    throw new ValueException(
                        sprintf('Validation of value depends on NULL property %s', $this->property),
                    );
                }

                $this->validateValue($value, $dependentValue);
            } catch (ReflectionException $reflectionException) {
                throw new ValueException(
                    sprintf('Validation of value depends on non-existent property %s', $this->property),
                    $reflectionException,
                );
            }
        } catch (ValueException $valueException) {
            throw PropertyValidationException::createFromValueException(
                $valueException,
                $this,
                $dtoProperty,
            );
        }
    }

    /**
     * @param mixed $value
     * @param mixed $dependentValue
     * @return void
     * @throws ValueException
     */
    abstract protected function validateValue(mixed $value, mixed $dependentValue): void;
}
