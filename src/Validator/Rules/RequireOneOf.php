<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\RequireOneOfValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use ReflectionException;
use ReflectionObject;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class RequireOneOf implements PropertyValidator
{
    use GetAttributeTrait;

    public function __construct(
        private string $group = 'default',
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        $reflect = new ReflectionObject($dto);
        $requireOneOfProperties = [];

        if ($value) {
            return;
        }

        try {
            $validateGroup = $this->getAttribute($reflect->getProperty($dtoProperty), self::class)->getGroup();

            foreach ($reflect->getProperties() as $reflectionProperty) {
                $requireOneOf = $this->getAttribute($reflectionProperty, self::class);

                if ($requireOneOf && $requireOneOf->getGroup() === $validateGroup) {
                    $requireOneOfProperties[$reflectionProperty->getName()] = $reflectionProperty->isInitialized($dto)
                        && $reflectionProperty->getValue($dto) !== null;
                }
            }

            if ($requireOneOfProperties && !array_filter($requireOneOfProperties)) {
                throw new RequireOneOfValidationException($this, $dtoProperty, array_keys($requireOneOfProperties));
            }
        } catch (ReflectionException $e) {
            throw new PropertyValidationException($this, $dtoProperty, 'Property does not exist on DTO', $e);
        }
    }

    protected function getGroup(): string
    {
        return $this->group;
    }
}
