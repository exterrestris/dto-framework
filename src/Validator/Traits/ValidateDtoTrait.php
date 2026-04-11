<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Traits;

use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use Exterrestris\DtoFramework\Validator\CompositePropertyValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\Exceptions\RequiredPropertyException;
use Exterrestris\DtoFramework\Validator\PropertyPreferenceValidator;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\NoValidate;
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
                $invalidProperties[$property->getName()][] = new RequiredPropertyException($property->getName());

                continue;
            }

            $validatorAttributes = $this->getValidationAttributes($property, $enforcePreferences);

            if ($validatorAttributes) {
                foreach ($validatorAttributes as $validator) {
                    try {
                        $validator->validateProperty($property, $dto);
                    } catch (PropertyValidatorException $e) {
                        $invalidProperties[$property->getName()][] = $e;
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

                    return $validator instanceof CompositePropertyValidator ? $validator->getValidators() : [$validator];
                },
                $property->getAttributes(PropertyValidator::class, ReflectionAttribute::IS_INSTANCEOF)
            ))
        );
    }
}
