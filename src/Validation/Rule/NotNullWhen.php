<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rule\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNullWhen extends AbstractDependentPropertyRule
{
    use CompileArrayValuesTrait;

    public function __construct(
        string $property,
        private array $hasOneOfValues,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfValueIsNull,
    ) {
        parent::__construct($property, $nullDependentValueBehaviour);
    }

    protected function validateValueAgainst(mixed $value, mixed $dependentValue): void
    {
        if ($value !== null) {
            return;
        }

        if (in_array($dependentValue, $this->hasOneOfValues)) {
            throw new ValueException(sprintf(
                'Value is required when "%s" is %s',
                $this->property,
                $this->compileValues($this->hasOneOfValues)
            ));
        }
    }
}
