<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\Exceptions\RequireOneOfValidationException;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class RequireOneOf implements PropertyValidator
{
    use GetAttributeTrait;

    public function __construct(
        private string $group = 'default',
    ) {
    }

    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        $reflect = new ReflectionObject($forDto);
        $requireOneOfProperties = [];

        if ($dtoProperty->getValue($forDto)) {
            return;
        }

        try {
            $validateGroup = $this->getAttribute($dtoProperty, self::class)->getGroup();

            foreach ($reflect->getProperties() as $reflectionProperty) {
                $requireOneOf = $this->getAttribute($reflectionProperty, self::class);

                if ($requireOneOf && $requireOneOf->getGroup() === $validateGroup) {
                    $requireOneOfProperties[$reflectionProperty->getName()] = $reflectionProperty->isInitialized($forDto)
                        && $reflectionProperty->getValue($forDto) !== null;
                }
            }

            if ($requireOneOfProperties && !array_filter($requireOneOfProperties)) {
                throw new RequireOneOfValidationException($this, $dtoProperty->getName(), array_keys($requireOneOfProperties));
            }
        } catch (ReflectionException $e) {
            throw new PropertyValidationException($this, $dtoProperty->getName(), 'Property does not exist on DTO', $e);
        }
    }

    protected function getGroup(): string
    {
        return $this->group;
    }
}
