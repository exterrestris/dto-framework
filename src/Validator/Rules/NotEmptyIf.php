<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmptyIf extends NotEmptyWhen
{
    public function __construct(
        string $property,
        mixed $hasValue,
        NullDependentValue $nullDependentValueBehaviour = NullDependentValue::PassIfValueIsNull
    ) {
        parent::__construct($property, [$hasValue], $nullDependentValueBehaviour);
    }
}
