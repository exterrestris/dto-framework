<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Metadata\DateFormat;
use Exterrestris\DtoFramework\Utilities\DateRoundingMode;
use Exterrestris\DtoFramework\Utilities\DateRoundingUnit;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseDateException;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseDateIntervalException;
use Exterrestris\DtoFramework\Utilities\GetPropertyDateFormatTrait;
use Exterrestris\DtoFramework\Utilities\ParseDateIntervalTrait;
use Exterrestris\DtoFramework\Utilities\ParseDateTrait;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use ReflectionProperty;

abstract class AbstractDatePropertyValueValidator implements PropertyValidator, ValueValidator
{
    protected const DEFAULT_FORMAT = DateFormat::DEFAULT_DATE_FORMAT;

    use GetPropertyDateFormatTrait;
    use ParseDateTrait;
    use ParseDateIntervalTrait;

    /**
     * @param DateTimeImmutable|DateInterval|string $date
     * @param ?string $format
     * @param ?DateRoundingMode $roundingMode
     * @param ?DateRoundingUnit $roundingUnit
     */
    public function __construct(
        protected readonly DateTimeImmutable|DateInterval|string $date,
        protected readonly ?string $format = null,
        protected readonly ?DateRoundingMode $roundingMode = null,
        protected readonly ?DateRoundingUnit $roundingUnit = null,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        try {
            $this->validateValueAgainst($value);
        } catch (ConfigException $exception) {
            throw ValueValidatorConfigException::fromConfigException($exception, $this);
        } catch (ValueException $exception) {
            throw ValueValidationException::fromValueException($exception, $this);
        }
    }

    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        try {
            $this->validateValueAgainst($dtoProperty->getValue($forDto), $this->getDateFormat($dtoProperty));
        } catch (ConfigException $exception) {
            throw PropertyValidatorConfigException::fromConfigException($exception, $this, $dtoProperty->getName());
        } catch (ValueException $exception) {
            throw PropertyValidationException::fromValueException($exception, $this, $dtoProperty->getName());
        }
    }

    protected function validateValueAgainst(mixed $value, ?DateFormat $dateFormat = null): void
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof DateTimeInterface) {
            throw new ValueException('Value is not a date');
        }

        $this->checkDate($value, ...$this->getDateFromConfig($dateFormat));
    }

    abstract protected function checkDate(DateTimeInterface $value, DateTimeImmutable $date, string $dateFormat): void;

    private function getDateFromConfig(?DateFormat $dateFormat = null): array
    {
        $date = $this->date;
        $format = $this->format ?? $dateFormat?->getFormat() ?? self::DEFAULT_FORMAT;
        $roundingMode = $this->roundingMode ?? $dateFormat?->getRoundingMode() ?? DateRoundingMode::ToStart;
        $roundingUnit = $this->roundingUnit ?? $dateFormat?->getRoundingUnit() ?? DateRoundingUnit::fromDateFormat($format);

        if (is_string($date)) {
            try {
                // Try to parse as a relative date
                $interval = $this->parseDateInterval($date, $format, $roundingMode, $roundingUnit);

                if ($interval) {
                    $date = $interval->applyToDate($interval->getRelativeTo() ?? $this->getNowDateTime());
                } else {
                    $date = $this->parseDate($date, $format, $roundingMode, $roundingUnit);
                }
            } catch (ParseDateIntervalException|ParseDateException $exception) {
                throw new ConfigException($exception->getMessage(), $exception);
            }
        }

        if ($date instanceof DateInterval) {
            $date = $this->getNowDateTime()->add($date);
        }

        return [$date, $format];
    }
}
