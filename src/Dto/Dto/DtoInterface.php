<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto;

use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\Factory\AbstractFactory;
use Exterrestris\DtoFramework\Dto\Factory\Factory;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\BaseDto;

/**
 * Base DTO interface
 *
 * Extending {@link AbstractDto} rather than implementing this interface directly is recommended.
 *
 * DTOs implementing this interface:
 * - SHOULD provide getter and setter methods for their properties
 * - SHOULD be immutable, i.e. if fluid setters are provided they SHOULD return a new instance using `clone` rather
 *   than `$this`
 * - MUST provide fluid setter methods if using any class that extends {@link AbstractFactory} (such as the default
 *   {@link Factory} implementation), i.e. the setter must return either `$this` or (preferably) a new instance
 * - MAY provide a constructer to set their properties
 *
 * {@link AbstractDto} implements custom cloning logic to ensure immutability when dealing with nested DTOs
 */
#[BaseDto]
interface DtoInterface
{
    const DEFAULT_DATE_FORMAT = DateTimeInterface::RFC3339;
}
