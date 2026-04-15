<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Rule;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NoSerializeIfNull implements DataExtractorRule
{
}
