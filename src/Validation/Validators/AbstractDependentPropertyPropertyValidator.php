<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract readonly class AbstractDependentPropertyPropertyValidator implements PropertyValidator
{
    public function __construct(
        protected string $property,
        protected NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfNull,
    ) {
    }

    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        try {
            $dependentProperty = (new ReflectionClass($forDto))->getProperty($this->property);
            $dependentValue = $dependentProperty->isInitialized($forDto) ? $dependentProperty->getValue($forDto) : null;

            if ($dependentValue === null) {
                $passValidation = match ($this->nullDependentValueBehaviour) {
                    NullDependentValue::PassIfNull => true,
                    NullDependentValue::PassIfValueIsNull => $dtoProperty->getValue($forDto) === null,
                    NullDependentValue::FailIfNull => false,
                };

                if ($passValidation) {
                    return;
                }

                throw new PropertyValidationException(
                    $this,
                    $dtoProperty->getName(),
                    sprintf('Validation of value depends on NULL property %s', $this->property),
                );
            }

            $this->validateValueAgainst($dtoProperty->getValue($forDto), $dependentValue);
        } catch (ReflectionException $exception) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty->getName(),
                sprintf('Validation of value depends on non-existent property %s', $this->property),
                $exception
            );
        } catch (ValueException $exception) {
            throw PropertyValidationException::fromValueException(
                $exception,
                $this,
                $dtoProperty->getName(),
            );
        }
    }

    /**
     * @param mixed $value
     * @param mixed $dependentValue
     * @return void
     * @throws ValueException
     */
    abstract protected function validateValueAgainst(mixed $value, mixed $dependentValue): void;
}
