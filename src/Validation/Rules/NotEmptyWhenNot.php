<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validation\Rules\Traits\CompileArrayValuesTrait;
use Exterrestris\DtoFramework\Validation\Rules\Traits\EmptyValueTrait;
use Exterrestris\DtoFramework\Validation\Validators\AbstractDependentPropertyPropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmptyWhenNot extends AbstractDependentPropertyPropertyValidator
{
    use CompileArrayValuesTrait;
    use EmptyValueTrait;

    public function __construct(
        string $property,
        private array $hasOneOfValues,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::FailIfNull,
    ) {
        parent::__construct($property, $nullDependentValueBehaviour);
    }

    protected function validateValueAgainst(mixed $value, mixed $dependentValue): void
    {
        if (!$this->isEmpty($value)) {
            return;
        }

        if (!in_array($dependentValue, $this->hasOneOfValues)) {
            throw new ValueException(sprintf(
                'Value must not be empty when "%s" is not %s',
                $this->property,
                $this->compileValues($this->hasOneOfValues)
            ));
        }
    }
}
