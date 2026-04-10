<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Rules;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NullIfEmpty
{
}
