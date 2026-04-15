<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractDependentPropertyPropertyValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\Traits\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNullWhen extends AbstractDependentPropertyPropertyValidator
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
