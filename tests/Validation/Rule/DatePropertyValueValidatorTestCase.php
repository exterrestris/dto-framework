<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Utility\DateRoundingMode;
use Exterrestris\DtoFramework\Validation\Rule\AbstractDateRule;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @extends ValueValidatorTestCase<AbstractDateRule>
 * @phpstan-import-type valuePassesTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueFailsTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueInvalidRuntimeConfigTestCase from ValueValidatorTestCase
 * @phpstan-import-type propertyPassesTestCase from PropertyValueValidatorTestCaseTrait
 * @phpstan-import-type propertyFailsTestCase from PropertyValueValidatorTestCaseTrait
 * @phpstan-import-type propertyInvalidRuntimeConfigTestCase from PropertyValueValidatorTestCaseTrait
 */
abstract class DatePropertyValueValidatorTestCase extends PropertyValueValidatorTestCase
{

    protected function getValidator(array $validatorParams): ValueValidatorInterface
    {
        $validator = parent::getValidator($validatorParams);
        $validator->setNowDateTime(new DateTimeImmutable('2024-10-11 01:30:00'));

        return $validator;
    }

    /**
     * @return array<string, valuePassesTestCase>
     */
    public static function nullValuePassesValidationProvider(): array
    {
        return [

            'N100' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                null,
            ],
            'N101' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                ],
                null,
            ],
            'N102' => [
                [
                    '2024-10-10T00:00:00Z',
                ],
                null,
            ],
            'N103' => [
                [
                    DateInterval::createFromDateString('+2 days'),
                ],
                null,
            ],
            'N104' => [
                [
                    DateInterval::createFromDateString('-2 days'),
                ],
                null,
            ],
            'N105' => [
                [
                    '+2 days',
                ],
                null,
            ],
            'N106' => [
                [
                    '-2 days',
                ],
                null,
            ],
            'N107' => [
                [
                    '2 days',
                ],
                null,
            ],
            'N108' => [
                [
                    '2 days ago',
                ],
                null,
            ],
            'N109' => [
                [
                    '1 month 2 days',
                ],
                null,
            ],
            'N200' => [
                [
                    '1 month -2 days',
                ],
                null,
            ],
            'N201' => [
                [
                    '+1 month +2 days',
                ],
                null,
            ],
            'N202' => [
                [
                    '+1 month -2 days',
                ],
                null,
            ],
            'N203' => [
                [
                    '2 days before 2024-10-10',
                    'Y-m-d',
                ],
                null,
            ],
            'N204' => [
                [
                    '2 days after 2024-10-10',
                    'Y-m-d',
                ],
                null,
            ],
            'N205' => [
                [
                    '1 month 2 days before 2024-10-10',
                    'Y-m-d',
                ],
                null,
            ],
            'N206' => [
                [
                    '1 month 2 days after 2024-10-10',
                    'Y-m-d',
                ],
                null,
            ],
        ];
    }

    /**
     * @return array<string, valuePassesTestCase>
     */
    protected static function getDateTimeImmutableTestCases(): array
    {
        return [
            'A100' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'A101' => [
                [
                    new DateTimeImmutable('2024-10-10 00:00:00'),
                ],
                new DateTime('2024-10-10 00:00:00'),
            ],
            'A200' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                new DateTimeImmutable('2024-10-10 01:30:01'),
            ],
            'A201' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                new DateTime('2024-10-11 01:30:00'),
            ],
            'A202' => [
                [
                    new DateTimeImmutable('2024-10-10 00:00:00'),
                ],
                new DateTime('2024-10-11 00:00:00'),
            ],
            'A300' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                new DateTimeImmutable('2024-10-10 01:29:59'),
            ],
            'A301' => [
                [
                    new DateTimeImmutable('2024-10-10 01:30:00'),
                ],
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'A302' => [
                [
                    new DateTimeImmutable('2024-10-10 00:00:00'),
                ],
                new DateTime('2024-10-09 00:00:00'),
            ],
        ];
    }

    /**
     * @return array<string, valuePassesTestCase>
     */
    protected static function getDateStringTestCases(): array
    {
        return [
            'B100' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'B101' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'B102' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-10 23:59:59'),
            ],
            'B103' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:35:00'),
            ],
            'B104' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-10 01:35:00'),
            ],
            'B105' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-10 01:35:00'),
            ],
            'B200' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:30:01'),
            ],
            'B201' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-10 00:00:01'),
            ],
            'B202' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-11 00:00:00'),
            ],
            'B203' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:35:01'),
            ],
            'B204' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-10 01:35:01'),
            ],
            'B205' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-10 01:35:01'),
            ],
            'B300' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:29:59'),
            ],
            'B301' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-09 23:59:59'),
            ],
            'B302' => [
                [
                    '2024-10-10',
                    'Y-m-d',
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-10 23:59:58'),
            ],
            'B303' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::None,
                ],
                new DateTimeImmutable('2024-10-10 01:34:59'),
            ],
            'B304' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToStart,
                ],
                new DateTimeImmutable('2024-10-10 01:34:59'),
            ],
            'B305' => [
                [
                    '2024-10-10T01:35:00Z',
                    null,
                    DateRoundingMode::ToEnd,
                ],
                new DateTimeImmutable('2024-10-10 01:34:59'),
            ],
        ];
    }

    /**
     * @return array<string, valuePassesTestCase>
     */
    protected static function getDateIntervalTestCases(): array
    {
        return [
            'C100' => [
                [
                    DateInterval::createFromDateString('2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'C101' => [
                [
                    DateInterval::createFromDateString('+2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'C102' => [
                [
                    DateInterval::createFromDateString('-2 days'),
                ],
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'C200' => [
                [
                    DateInterval::createFromDateString('2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:30:01'),
            ],
            'C201' => [
                [
                    DateInterval::createFromDateString('+2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:30:01'),
            ],
            'C202' => [
                [
                    DateInterval::createFromDateString('-2 days'),
                ],
                new DateTimeImmutable('2024-10-09 01:30:01'),
            ],
            'C300' => [
                [
                    DateInterval::createFromDateString('2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:29:59'),
            ],
            'C301' => [
                [
                    DateInterval::createFromDateString('+2 days'),
                ],
                new DateTimeImmutable('2024-10-13 01:29:59'),
            ],
            'C302' => [
                [
                    DateInterval::createFromDateString('-2 days'),
                ],
                new DateTimeImmutable('2024-10-09 01:29:59'),
            ],
        ];
    }

    /**
     * @return array<string, valuePassesTestCase>
     */
    protected static function getRelativeDateStringTestCases(): array
    {
        return [
            'D100' => [['2 days',],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D101' => [['2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D102' => [['2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D103' => [['2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D104' => [['2days',],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D105' => [['2days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D106' => [['2days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D107' => [['2days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D108' => [['+2 days',],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D109' => [['+2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D110' => [['+2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D111' => [['+2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D112' => [['-2 days',],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D113' => [['-2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D114' => [['-2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D115' => [['-2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D116' => [['2 days after now',],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D117' => [['2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D118' => [['2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D119' => [['2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D120' => [['2 days before now',],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D121' => [['2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D122' => [['2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D123' => [['2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D124' => [['2 days after today',],new DateTimeImmutable('2024-10-13 00:00:00'),],
            'D125' => [['2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D126' => [['2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 00:00:00'),],
            'D127' => [['2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 23:59:59'),],
            'D128' => [['2 days before today',],new DateTimeImmutable('2024-10-09 00:00:00'),],
            'D129' => [['2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:00'),],
            'D130' => [['2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 00:00:00'),],
            'D131' => [['2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 23:59:59'),],
            'D132' => [['1 month 2 days',],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D133' => [['1 month 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D134' => [['1 month 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D135' => [['1 month 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D136' => [['1 month + 2 days',],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D137' => [['1 month + 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D138' => [['1 month + 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D139' => [['1 month + 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D140' => [['+1 month +2 days',],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D141' => [['+1 month +2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D142' => [['+1 month +2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D143' => [['+1 month +2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D144' => [['1 month - 2 days',],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D145' => [['1 month - 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D146' => [['1 month - 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D147' => [['1 month - 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D148' => [['+1 month -2 days',],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D149' => [['+1 month -2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D150' => [['+1 month -2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D151' => [['+1 month -2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:30:00'),],
            'D152' => [['1 month 2 days after now',],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D153' => [['1 month 2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D154' => [['1 month 2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D155' => [['1 month 2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D156' => [['1 month 2 days before now',],new DateTimeImmutable('2024-09-09 01:30:00'),],
            'D157' => [['1 month 2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:30:00'),],
            'D158' => [['1 month 2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-09 01:30:00'),],
            'D159' => [['1 month 2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-09 01:30:00'),],
            'D160' => [['1 month 2 days after today',],new DateTimeImmutable('2024-11-13 00:00:00'),],
            'D161' => [['1 month 2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:00'),],
            'D162' => [['1 month 2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 00:00:00'),],
            'D163' => [['1 month 2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 23:59:59'),],
            'D164' => [['1 month 2 days before today',],new DateTimeImmutable('2024-09-09 00:00:00'),],
            'D165' => [['1 month 2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:30:00'),],
            'D166' => [['1 month 2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-09 00:00:00'),],
            'D167' => [['1 month 2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-09 23:59:59'),],
            'D168' => [['1 month 2 days after 01/02/2024','d/m/Y'],new DateTimeImmutable('2024-03-03 00:00:00'),],
            'D169' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2024-03-03 01:30:00'),],
            'D170' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2024-03-03 00:00:00'),],
            'D171' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2024-03-03 23:59:59'),],
            'D172' => [['1 month 2 days before 01/02/2024','d/m/Y'],new DateTimeImmutable('2023-12-30 00:00:00'),],
            'D173' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2023-12-30 01:30:00'),],
            'D174' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2023-12-30 00:00:00'),],
            'D175' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2023-12-30 23:59:59'),],

            'D200' => [['2 days',],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D201' => [['2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D202' => [['2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D203' => [['2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D204' => [['2days',],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D205' => [['2days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D206' => [['2days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D207' => [['2days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D208' => [['+2 days',],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D209' => [['+2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D210' => [['+2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D211' => [['+2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D212' => [['-2 days',],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D213' => [['-2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D214' => [['-2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D215' => [['-2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D216' => [['2 days after now',],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D217' => [['2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D218' => [['2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D219' => [['2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D220' => [['2 days before now',],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D221' => [['2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D222' => [['2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D223' => [['2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D224' => [['2 days after today',],new DateTimeImmutable('2024-10-13 00:00:01'),],
            'D225' => [['2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:30:01'),],
            'D226' => [['2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 00:00:01'),],
            'D227' => [['2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-14 00:00:00'),],
            'D228' => [['2 days before today',],new DateTimeImmutable('2024-10-09 00:00:01'),],
            'D229' => [['2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:30:01'),],
            'D230' => [['2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 00:00:01'),],
            'D231' => [['2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-10 00:00:00'),],
            'D232' => [['1 month 2 days',],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D233' => [['1 month 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D234' => [['1 month 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D235' => [['1 month 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D236' => [['1 month + 2 days',],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D237' => [['1 month + 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D238' => [['1 month + 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D239' => [['1 month + 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D240' => [['+1 month +2 days',],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D241' => [['+1 month +2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D242' => [['+1 month +2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D243' => [['+1 month +2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D244' => [['1 month - 2 days',],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D245' => [['1 month - 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D246' => [['1 month - 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D247' => [['1 month - 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D248' => [['+1 month -2 days',],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D249' => [['+1 month -2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D250' => [['+1 month -2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D251' => [['+1 month -2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:30:01'),],
            'D252' => [['1 month 2 days after now',],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D253' => [['1 month 2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D254' => [['1 month 2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D255' => [['1 month 2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D256' => [['1 month 2 days before now',],new DateTimeImmutable('2024-09-09 01:30:01'),],
            'D257' => [['1 month 2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:30:01'),],
            'D258' => [['1 month 2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-09 01:30:01'),],
            'D259' => [['1 month 2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-09 01:30:01'),],
            'D260' => [['1 month 2 days after today',],new DateTimeImmutable('2024-11-13 00:00:01'),],
            'D261' => [['1 month 2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:30:01'),],
            'D262' => [['1 month 2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 00:00:01'),],
            'D263' => [['1 month 2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-14 00:00:00'),],
            'D264' => [['1 month 2 days before today',],new DateTimeImmutable('2024-09-09 00:00:01'),],
            'D265' => [['1 month 2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:30:01'),],
            'D266' => [['1 month 2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-09 00:00:01'),],
            'D267' => [['1 month 2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-10 00:00:00'),],
            'D268' => [['1 month 2 days after 01/02/2024','d/m/Y'],new DateTimeImmutable('2024-03-03 00:00:01'),],
            'D269' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2024-03-03 01:30:01'),],
            'D270' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2024-03-03 00:00:01'),],
            'D271' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2024-03-04 00:00:00'),],
            'D272' => [['1 month 2 days before 01/02/2024','d/m/Y'],new DateTimeImmutable('2023-12-30 00:00:01'),],
            'D273' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2023-12-30 01:30:01'),],
            'D274' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2023-12-30 00:00:01'),],
            'D275' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2023-12-31 00:00:00'),],

            'D300' => [['2 days',],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D301' => [['2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D302' => [['2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D303' => [['2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D304' => [['2days',],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D305' => [['2days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D306' => [['2days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D307' => [['2days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D308' => [['+2 days',],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D309' => [['+2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D310' => [['+2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D311' => [['+2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D312' => [['-2 days',],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D313' => [['-2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D314' => [['-2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D315' => [['-2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D316' => [['2 days after now',],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D317' => [['2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D318' => [['2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D319' => [['2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D320' => [['2 days before now',],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D321' => [['2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D322' => [['2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D323' => [['2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D324' => [['2 days after today',],new DateTimeImmutable('2024-10-12 23:59:59'),],
            'D325' => [['2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-13 01:29:59'),],
            'D326' => [['2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-12 23:59:59'),],
            'D327' => [['2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-13 23:59:58'),],
            'D328' => [['2 days before today',],new DateTimeImmutable('2024-10-08 23:59:59'),],
            'D329' => [['2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-10-09 01:29:59'),],
            'D330' => [['2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-10-08 23:59:59'),],
            'D331' => [['2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-10-09 23:59:58'),],
            'D332' => [['1 month 2 days',],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D333' => [['1 month 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D334' => [['1 month 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D335' => [['1 month 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D336' => [['1 month + 2 days',],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D337' => [['1 month + 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D338' => [['1 month + 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D339' => [['1 month + 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D340' => [['+1 month +2 days',],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D341' => [['+1 month +2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D342' => [['+1 month +2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D343' => [['+1 month +2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D344' => [['1 month - 2 days',],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D345' => [['1 month - 2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D346' => [['1 month - 2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D347' => [['1 month - 2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D348' => [['+1 month -2 days',],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D349' => [['+1 month -2 days',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D350' => [['+1 month -2 days',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D351' => [['+1 month -2 days',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-09 01:29:59'),],
            'D352' => [['1 month 2 days after now',],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D353' => [['1 month 2 days after now',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D354' => [['1 month 2 days after now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D355' => [['1 month 2 days after now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D356' => [['1 month 2 days before now',],new DateTimeImmutable('2024-09-09 01:29:59'),],
            'D357' => [['1 month 2 days before now',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:29:59'),],
            'D358' => [['1 month 2 days before now',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-09 01:29:59'),],
            'D359' => [['1 month 2 days before now',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-09 01:29:59'),],
            'D360' => [['1 month 2 days after today',],new DateTimeImmutable('2024-11-12 23:59:59'),],
            'D361' => [['1 month 2 days after today',null,DateRoundingMode::None],new DateTimeImmutable('2024-11-13 01:29:59'),],
            'D362' => [['1 month 2 days after today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-11-12 23:59:59'),],
            'D363' => [['1 month 2 days after today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-11-13 23:59:58'),],
            'D364' => [['1 month 2 days before today',],new DateTimeImmutable('2024-09-08 23:59:59'),],
            'D365' => [['1 month 2 days before today',null,DateRoundingMode::None],new DateTimeImmutable('2024-09-09 01:29:59'),],
            'D366' => [['1 month 2 days before today',null,DateRoundingMode::ToStart],new DateTimeImmutable('2024-09-08 23:59:59'),],
            'D367' => [['1 month 2 days before today',null,DateRoundingMode::ToEnd],new DateTimeImmutable('2024-09-09 23:59:58'),],
            'D368' => [['1 month 2 days after 01/02/2024','d/m/Y'],new DateTimeImmutable('2024-03-02 23:59:59'),],
            'D369' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2024-03-03 01:29:59'),],
            'D370' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2024-03-02 23:59:59'),],
            'D371' => [['1 month 2 days after 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2024-03-03 23:59:58'),],
            'D372' => [['1 month 2 days before 01/02/2024','d/m/Y'],new DateTimeImmutable('2023-12-29 23:59:59'),],
            'D373' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::None],new DateTimeImmutable('2023-12-30 01:29:59'),],
            'D374' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToStart],new DateTimeImmutable('2023-12-29 23:59:59'),],
            'D375' => [['1 month 2 days before 01/02/2024','d/m/Y',DateRoundingMode::ToEnd],new DateTimeImmutable('2023-12-30 23:59:58'),],
        ];
    }

    /**
     * @return array<string, valueFailsTestCase>
     */
    protected static function getAllValueTestCases(): array
    {
        return [
            ...self::getDateTimeImmutableTestCases(),
            ...self::getDateStringTestCases(),
            ...self::getDateIntervalTestCases(),
            ...self::getRelativeDateStringTestCases(),
        ];
    }

    /**
     * @return array<string, bool>
     */
    abstract protected static function getTestCasePassFailMap(): array;

    /**
     * @return valuePassesTestCase[]
     */
    public static function valuePassesValidationProvider(): array
    {
        return array_intersect_key(self::getAllValueTestCases(), array_filter(static::getTestCasePassFailMap()));
    }

    /**
     * @return valuePassesTestCase[]
     */
    public static function valueFailsValidationProvider(): array
    {
        return array_diff_key(self::getAllValueTestCases(), array_filter(static::getTestCasePassFailMap()));
    }

    #[DataProvider('nullValuePassesValidationProvider')]
    #[DataProvider('valuePassesValidationProvider')]
    public function testValidateValuePasses(array $validatorParams, mixed $value): void
    {
        parent::testValidateValuePasses($validatorParams, $value);
    }

    /**
     * @return propertyPassesTestCase[]
     */
    public static function nullPropertyPassesValidationProvider(): array
    {
        return static::createPropertyPassesFromValuePasses(static::valuePassesValidationProvider());
    }

    /**
     * @return propertyPassesTestCase[]
     */
    protected static function getAdditionalPropertyTestCases(): array
    {
        return [
            'E000' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto()), 'date'
            ],
            'E100' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-10 00:00:00')), 
                'date'
            ],
            'E101' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-10 23:59:59')),
                'endDate'
            ],
            'E102' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-10 01:30:00')), 
                'otherDate'
            ],
            'E200' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-10 00:00:01')), 
                'date'
            ],
            'E201' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-11 00:00:00')), 
                'endDate'
            ],
            'E202' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-11 01:30:01')), 
                'otherDate'
            ],
            'E300' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-09 23:59:59')),
                'date'
            ],
            'E301' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-10 23:59:58')), 
                'endDate'
            ],
            'E302' => [
                [
                    '10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-10 01:29:59')), 
                'otherDate'
            ],
            'E400' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-12 00:00:00')), 
                'date'
            ],
            'E401' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-12 23:59:59')), 
                'endDate'
            ],
            'E402' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-12 01:30:00')), 
                'otherDate'
            ],
            'E500' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-12 00:00:01')),
                'date'
            ],
            'E501' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-13 00:00:00')),
                'endDate'
            ],
            'E502' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-13 01:30:01')),
                'otherDate'
            ],
            'E600' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-11 23:59:59')), 
                'date'
            ],
            'E601' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-12 23:59:58')),
                'endDate'
            ],
            'E602' => [
                [
                    '2 days after 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-12 01:29:59')),
                'otherDate'
            ],
            'E700' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-08 00:00:00')), 
                'date'
            ],
            'E701' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-08 23:59:59')), 
                'endDate'
            ],
            'E702' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-08 01:30:00')), 
                'otherDate'
            ],
            'E800' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-08 00:00:01')), 
                'date'
            ],
            'E801' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-09 00:00:00')),
                'endDate'
            ],
            'E802' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-09 01:30:01')), 
                'otherDate'
            ],
            'E900' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setDate(new DateTimeImmutable('2024-10-07 23:59:59')),
                'date'
            ],
            'E901' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setEndDate(new DateTimeImmutable('2024-10-08 23:59:58')), 
                'endDate'
            ],
            'E902' => [
                [
                    '2 days before 10/10/2024',
                ], 
                (new MockDto())->setOtherDate(new DateTimeImmutable('2024-10-08 01:29:59')),
                'otherDate'
            ],
        ];
    }

    /**
     * @return propertyPassesTestCase[]
     */
    public static function additionalPropertyPassesValidationProvider(): array
    {
        return array_intersect_key(self::getAdditionalPropertyTestCases(), array_filter(static::getTestCasePassFailMap()));
    }

    /**
     * @return propertyPassesTestCase[]
     */
    public static function additionalPropertyFailsValidationProvider(): array
    {
        return array_diff_key(self::getAdditionalPropertyTestCases(), array_filter(static::getTestCasePassFailMap()));
    }

    #[DataProvider('nullPropertyPassesValidationProvider')]
    #[DataProvider('additionalPropertyPassesValidationProvider')]
    public function testValidatePropertyPasses(array $validatorParams, DtoInterface $dto, string $property): void
    {
        parent::testValidatePropertyPasses($validatorParams, $dto, $property);
    }

    #[DataProvider('propertyFailsValidationProvider')]
    #[DataProvider('additionalPropertyFailsValidationProvider')]
    public function testValidatePropertyFails(
        array $validatorParams,
        DtoInterface $dto,
        string $property,
        ?string $exceptionMessage = null,
        ?string $exceptionType = null,
    ): void {
        parent::testValidatePropertyFails(
            $validatorParams,
            $dto,
            $property,
            $exceptionMessage,
            $exceptionType,
        );
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            'Q100' => [
                [
                    '2024-10-10',
                    'd/m/Y',
                ],
                new DateTimeImmutable('2024-10-11 01:30:00'),
            ],
            'Q101' => [
                [
                    '2024-10-10 01:30:00Z',
                ],
                new DateTimeImmutable('2024-10-11 01:30:00'),
            ],
            'Q102' => [
                [
                    '+2 dys',
                ],
                new DateTimeImmutable('2024-10-14 01:30:00'),
            ],
            'Q103' => [
                [
                    '-2 days -2 days',
                ],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'Q104' => [
                [
                    '-2 days + 1day',
                ],
                new DateTimeImmutable('2024-10-09 00:00:01'),
            ],
            'Q105' => [
                [
                    '2 mins + 1 minute',
                ],
                new DateTimeImmutable('2024-10-14 01:30:00'),
            ],
            'Q106' => [
                [
                    '+2 days ago',
                ],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'Q107' => [
                [
                    '-2 days ago',
                ],
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'Q108' => [
                [
                    '2 days before 10/12/2024',
                    'Y-m-d',
                ],
                new DateTimeImmutable('2024-12-08 00:00:01'),
            ],
            'Q109' => [
                [
                    '2 days after 10/12/2024',
                    'Y-m-d',
                ],
                new DateTimeImmutable('2024-12-12 00:00:01'),
            ],
            'Q110' => [
                [
                    '1 month 2 days before 10/12/2024',
                ],
                new DateTimeImmutable('2024-11-08 00:00:01'),
            ],
            'Q111' => [
                [
                    '1 month 2 days after 10/12/2024',
                ],
                new DateTimeImmutable('2025-01-12 00:00:01'),
            ],
        ];
    }

    public static function propertyWithInvalidConfigProvider(): array
    {
        return [
            ...parent::propertyWithInvalidConfigProvider(),
            ...[
                'R100' => [
                    [
                        '2024-10-11'
                    ],
                    (new MockDto())
                        ->setDate(new DateTimeImmutable('2024-10-11 01:30:00')),
                    'date'
                ],
                'R101' => [
                    [
                        '1 month after 2024-10-11'
                    ],
                    (new MockDto())
                        ->setDate(new DateTimeImmutable('2024-10-11 01:30:00')),
                    'date'
                ],
            ]
        ];
    }
}
