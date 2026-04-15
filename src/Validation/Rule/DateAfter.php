<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DateAfter extends AbstractDateRule
{
    /**
     * @param DateTimeInterface $value
     * @param DateTimeImmutable $date
     * @param string $dateFormat
     * @return void
     */
    protected function checkDate(DateTimeInterface $value, DateTimeImmutable $date, string $dateFormat): void
    {
        if ($value <= $date) {
            throw new ValueException(sprintf('Value must be after %s', $date->format($dateFormat)));
        }
    }
}
