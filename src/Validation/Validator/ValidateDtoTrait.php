<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
use Exterrestris\DtoFramework\Validation\Exception\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exception\InvalidDtoPropertyException;
use Exterrestris\DtoFramework\Validation\Exception\MissingRequiredDtoPropertyException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\Rule\NoValidate;
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
     * @return PropertyValidatorInterface[]
     */
    protected function getValidationAttributes(ReflectionProperty $property, bool $enforcePreferences = false): array
    {
        return array_map(
            static function (PropertyValidatorInterface $validator) use ($enforcePreferences) {
                if ($validator instanceof PropertyPreferenceValidatorInterface) {
                    return $enforcePreferences ? $validator->getPreference() : $validator->getRequirement();
                }

                return $validator;
            },
            array_merge(...array_map(
                static function (ReflectionAttribute $attribute): array {
                    $validator = $attribute->newInstance();

                    return $validator instanceof CompositeValueValidatorInterface ? $validator->getValidators() : [$validator];
                },
                $property->getAttributes(PropertyValidatorInterface::class, ReflectionAttribute::IS_INSTANCEOF)
            ))
        );
    }
}
