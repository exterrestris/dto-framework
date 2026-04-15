<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utilities;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseDateException;


/**
 * Utility trait for parsing date strings using a provided format into {@see DateTimeImmutable} instances
 */
trait ParseDateTrait
{
    use GetCurrentDateTimeTrait;

    /**
     * @param string $date
     * @param string $usingFormat
     * @param ?DateRoundingMode $roundingMode
     * @param ?DateRoundingUnit $roundingUnit
     * @return DateTimeImmutable
     * @throws ParseDateException
     */
    protected function parseDate(
        string $date,
        string $usingFormat,
        ?DateRoundingMode $roundingMode = null,
        ?DateRoundingUnit $roundingUnit = null,
    ): DateTimeImmutable {
        $roundingMode = $roundingMode ?? DateRoundingMode::ToStart;
        $formatRoundingUnit = DateRoundingUnit::fromDateFormat($usingFormat);

        $parsedDate = DateTimeImmutable::createFromFormat($usingFormat, $date);

        if ($parsedDate === false) {
            throw new ParseDateException($date, $usingFormat);
        }

        return match ($roundingMode) {
            default => $roundingMode->round($parsedDate, $roundingUnit ?? $formatRoundingUnit),
            /**
             * @internal For `$date` strings that do not include a time element, the time portion of the `$parsedDate`
             *           returned by {@see DateTimeImmutable::createFromFormat()} will be that of the current system
             *           time. So that the functionality provided by {@link self::parseDate()} can be tested,
             *           {@link self::setNowDateTime()} allows "now" to be fixed to a defined value - to accommodate
             *           this when `$roundingMode` is {@link DateRoundingMode::None}, we need to reset the time portion
             *           of `$parsedDate` to that provided by {@link self::setNowDateTime()}. This adjustment
             *           effectively does nothing when "now" has not been overridden
             */
            DateRoundingMode::None => $formatRoundingUnit->roundToStart($parsedDate)->add(
                $formatRoundingUnit->roundToStart($this->getNowDateTime())->diff($this->getNowDateTime())
            ),
        };
    }
}
