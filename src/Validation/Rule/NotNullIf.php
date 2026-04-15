<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Rule\Configuration\NullDependentValueBehaviour as NullDependentValue;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNullIf extends NotNullWhen
{
    public function __construct(
        string $property,
        mixed $hasValue,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfValueIsNull
    ) {
        parent::__construct($property, [$hasValue], $nullDependentValueBehaviour);
    }
}
