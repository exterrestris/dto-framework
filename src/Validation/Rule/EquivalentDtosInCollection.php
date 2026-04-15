<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Closure;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rule\CompileArrayValuesTrait;
use ReflectionClass;
use ReflectionException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class EquivalentDtosInCollection extends AbstractDependentPropertyRule
{
    use CompileArrayValuesTrait;

    private string $dtoProperty;
    private string $dependentDtoProperty;

    public function __construct(
        string $property,
        string $dtoProperty,
        ?string $dependentDtoProperty = null,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfValueIsNull,
    ) {
        parent::__construct($property, $nullDependentValueBehaviour);

        $this->dtoProperty = $dtoProperty;
        $this->dependentDtoProperty = $dependentDtoProperty ?? $dtoProperty;
    }

    protected function validateValueAgainst(mixed $value, mixed $dependentValue): void
    {
        if ($value === null) {
            if ($dependentValue !== null) {
                throw new ValueException(sprintf('Value is not set when "%s" is set', $this->property));
            }

            return;
        }

        if (!$value instanceof CollectionInterface) {
            throw new ValueException('Value is not a collection');
        }

        if (!$dependentValue instanceof CollectionInterface) {
            throw new ValueException(sprintf('"%s" is not a collection', $this->property));
        }

        if ($value->count() === 0 && $dependentValue->count() === 0) {
            return;
        }

        if ($value->count() < $dependentValue->count()) {
            throw new ValueException(sprintf('Collection contains fewer DTOs than "%s"', $this->property));
        }

        $dtoValues = $value->mapToUniqueArray($this->getMapperFn($this->dtoProperty));
        $dependentValues = $dependentValue->mapToUniqueArray($this->getMapperFn($this->dependentDtoProperty));

        $missingValues = array_diff($dtoValues, $dependentValues);

        if ($missingValues) {
            throw new ValueException(sprintf(
                'Equivalent %s missing from "%s"',
                $this->compileValuesUsingFormat(
                    $missingValues,
                    '"%s" DTO',
                    '"%s" and "%s" DTOs',
                ),
                $this->property,
            ));
        }
    }

    private function getMapperFn(string $property): Closure
    {
        return static function (DtoInterface $dto) use ($property) {
            try {
                $reflect = new ReflectionClass($dto);
                $reflectProperty = $reflect->getProperty($property);

                if (!$reflectProperty->isInitialized($dto)) {
                    throw new ValueException(sprintf('"%s" is not set on collection DTO', $property));
                }

                return $reflectProperty->getValue($dto);
            } catch (ReflectionException) {
                throw new ValueException(sprintf('"%s" does not exist on collection DTO', $property));
            }
        };
    }
}
