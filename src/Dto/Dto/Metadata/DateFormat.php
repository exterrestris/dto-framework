<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Metadata;

use Attribute;
use DateTimeInterface;
use Exterrestris\DtoFramework\Utility\DateRoundingMode;
use Exterrestris\DtoFramework\Utility\DateRoundingUnit;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS)]
final readonly class DateFormat
{
    public const DEFAULT_DATE_FORMAT = DateTimeInterface::RFC3339;

    public function __construct(
        private string $format,
        private ?DateRoundingMode $roundingMode = null,
        private ?DateRoundingUnit $roundingUnit = null,
    ) {
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getRoundingMode(): ?DateRoundingMode
    {
        return $this->roundingMode;
    }

    public function getRoundingUnit(): ?DateRoundingUnit
    {
        return $this->roundingUnit;
    }
}
