<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\DateFormat;
use Exterrestris\DtoFramework\Utility\DateRoundingMode;
use Exterrestris\DtoFramework\Utility\DateRoundingUnit;
use Exterrestris\DtoFramework\Utility\Exception\ParseDateException;
use Exterrestris\DtoFramework\Utility\Exception\ParseDateIntervalException;
use Exterrestris\DtoFramework\Utility\GetPropertyDateFormatTrait;
use Exterrestris\DtoFramework\Utility\ParseDateIntervalTrait;
use Exterrestris\DtoFramework\Utility\ParseDateTrait;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use ReflectionProperty;

abstract class AbstractDateRule implements PropertyValidatorInterface, ValueValidatorInterface
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
