<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Traits;

use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use Exterrestris\DtoFramework\Validation\CompositeValueValidator;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoPropertyException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\Exceptions\MissingRequiredDtoPropertyException;
use Exterrestris\DtoFramework\Validation\PropertyPreferenceValidator;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\NoValidate;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

/**
 * @template Dto of DtoInterface
 */
trait ValidateDtoTrait
{
    use GetAttributeTrait;

    /**
     * @param Dto $dto
     * @param bool $enforcePreferences
     * @return Dto
     * @throws InvalidDtoException
     */
    protected function validateDto(DtoInterface $dto, bool $enforcePreferences = false): DtoInterface
    {
        $invalidProperties = [];

        $reflect = new ReflectionObject($dto);

        if ($this->getAttribute($reflect, NoValidate::class)) {
            return $dto;
        }

        foreach ($reflect->getProperties() as $property) {
            if (
                $this->getAttribute($property, NoValidate::class) ||
                $this->getAttribute($property, Internal::class)
            ) {
                continue;
            }

            if (!$property->isInitialized($dto) && !$property->getType()->allowsNull()) {
                $invalidProperties[] = new MissingRequiredDtoPropertyException($dto, $property->getName());

                continue;
            }

            $validatorAttributes = $this->getValidationAttributes($property, $enforcePreferences);

            if ($validatorAttributes) {
                foreach ($validatorAttributes as $validator) {
                    try {
                        $validator->validateProperty($property, $dto);
                    } catch (PropertyValidatorException $e) {
                        $invalidProperties[] = InvalidDtoPropertyException::from($e, $dto, $property->getName());
                    }
                }
            }
        }

        if ($invalidProperties) {
            throw new InvalidDtoException($dto, $invalidProperties);
        }

        return $dto;
    }


    /**
     * @param ReflectionProperty $property
     * @param bool $enforcePreferences
     * @return PropertyValidator[]
     */
    protected function getValidationAttributes(ReflectionProperty $property, bool $enforcePreferences = false): array
    {
        return array_map(
            static function (PropertyValidator $validator) use ($enforcePreferences) {
                if ($validator instanceof PropertyPreferenceValidator) {
                    return $enforcePreferences ? $validator->getPreference() : $validator->getRequirement();
                }

                return $validator;
            },
            array_merge(...array_map(
                static function (ReflectionAttribute $attribute): array {
                    $validator = $attribute->newInstance();

                    return $validator instanceof CompositeValueValidator ? $validator->getValidators() : [$validator];
                },
                $property->getAttributes(PropertyValidator::class, ReflectionAttribute::IS_INSTANCEOF)
            ))
        );
    }
}
