<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractDatePropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DateAfter extends AbstractDatePropertyValueValidator
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
