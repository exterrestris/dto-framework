<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Attributes;

use Attribute;
use Exterrestris\DtoFramework\Comparators\IdenticalComparator;
use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Dto\Factory\FactoryInterface;

/**
 * @internal Marks a property as being internal to the class implementation. Properties marked with this attribute are
 *           excluded from validation, serialization and comparison (except by {@link IdenticalComparator}). Values are
 *           NOT considered to be immutable, even when the DTO is otherwise immutable (as recommended), therefore
 *           getters and setters SHOULD NOT be defined for these properties. The default implementations of
 *           {@link DtoInterface} and {@link FactoryInterface} provided by {@link AbstractDto} and {@link Factory}
 *           do not allow these properties to be set as part of any DTO data provided to {@link AbstractDto::__construct()},
 *           {@link AbstractDto::with()} or {@link Factory::create()}. When cloning an instance of {@link AbstractDto},
 *           object values for these properties are NOT cloned as for other object values, but are copied by reference,
 *           therefore object values for these properties are not expected to be unique per DTO instance
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Internal
{
}
