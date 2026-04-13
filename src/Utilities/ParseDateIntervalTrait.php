<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utilities;

use DateInterval;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseDateException;
use Exterrestris\DtoFramework\Utilities\Exceptions\ParseDateIntervalException;

/**
 * Utility trait for parsing relative date strings into {@see DateInterval} instances
 */
trait ParseDateIntervalTrait
{
    use ParseDateTrait;

    /**
     * @param string $interval
     * @param ?string $dateFormat
     * @param ?DateRoundingMode $roundingMode
     * @param ?DateRoundingUnit $roundingUnit
     * @return ParsedDateInterval|false Returns `false` if `$interval` doesn't contains at least one date interval string
     */
    protected function parseDateInterval(
        string $interval,
        ?string $dateFormat,
        ?DateRoundingMode $roundingMode = null,
        ?DateRoundingUnit $roundingUnit = null,
    ): ParsedDateInterval|false {
        $patterns = [
            /**
             * Match a relative date that must be in the past, e.g. "2 days ago", "1 month before 25/12/2026" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*) (?:ago|before (?<relativeTo>.+))$/' =>
                static fn(string $timeframe): string => '-' . $timeframe,
            /**
             * Match a relative date that must be in the future, e.g. "2 days", "1 month from 25/12/2026" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*) (?:from|after) (?<relativeTo>.+)$/' =>
                static fn(string $timeframe): string => $timeframe,
            /**
             * Match a relative date that could be in the past or the future, e.g. "+2 days", "-1 week" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>(?:[+-] ?)?[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*)$/' =>
                static fn(string $timeframe): string => $timeframe,
        ];

        if (preg_match_all(
            '/(?<intervals>(?:[+-] ?)?[0-9]+ ?(?<types>year|month|week|day|hour|min(?:ute)?)s?)/',
            $interval,
            $timeframes,
        ) === 0) {
            return false;
        }

        // Prevent intervals such as "2 days -1 day"
        $timeframes['types'] = str_replace('minute', 'min', $timeframes['types']);

        if (max(array_count_values($timeframes['types'])) > 1) {
            throw new ParseDateIntervalException($interval);
        }

        $timeframes['intervals'] = str_replace(['- ', '+ '], ['-', '+'], $timeframes['intervals']);

        $relativeTo = null;
        $relativeToRoundingMode = DateRoundingMode::None;

        foreach ($patterns as $pattern => $mapper) {
            if (!preg_match($pattern, $interval, $matches)) {
                continue;
            }

            if (isset($matches['relativeTo'])) {
                if ($dateFormat === null) {
                    throw new ParseDateIntervalException($interval);
                }

                try {
                    $relativeTo = match($matches['relativeTo']) {
                        'now', 'today' => null,
                        default => $this->parseDate(
                            $matches['relativeTo'],
                            $dateFormat,
                            $roundingMode,
                            $roundingUnit
                        ),
                    };

                    $relativeToRoundingMode = match($matches['relativeTo']) {
                        'today' => $roundingMode,
                        default => DateRoundingMode::None,
                    };
                } catch (ParseDateException $e) {
                    throw new ParseDateIntervalException($interval, $e);
                }
            }

            $intervals = array_filter(
                array_map(
                    static fn(string $timeframe) => DateInterval::createFromDateString($timeframe),
                    array_map($mapper, $timeframes['intervals'])
                )
            );

            if (count($intervals) === count($timeframes['intervals'])) {
                return new ParsedDateInterval($interval, $intervals, $relativeTo, $relativeToRoundingMode);
            }
        }

        throw new ParseDateIntervalException($interval);
    }
}
