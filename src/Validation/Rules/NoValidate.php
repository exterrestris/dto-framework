<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS)]
final class NoValidate
{
}
