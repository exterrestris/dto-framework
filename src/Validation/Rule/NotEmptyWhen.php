<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rule\CompileArrayValuesTrait;
use Exterrestris\DtoFramework\Validation\Rule\EmptyValueTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmptyWhen extends AbstractDependentPropertyRule
{
    use CompileArrayValuesTrait;
    use EmptyValueTrait;

    public function __construct(
        string $property,
        private array $hasOneOfValues,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfValueIsNull,
    ) {
        parent::__construct($property, $nullDependentValueBehaviour);
    }

    protected function validateValueAgainst(mixed $value, mixed $dependentValue): void
    {
        if (!$this->isEmpty($value)) {
            return;
        }

        if (in_array($dependentValue, $this->hasOneOfValues)) {
            throw new ValueException(sprintf(
                'Value must not be empty when "%s" is %s',
                $this->property,
                $this->compileValues($this->hasOneOfValues)
            ));
        }
    }
}
