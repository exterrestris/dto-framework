<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorException;

/**
 * @phpstan-import-type valuePassesTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueFailsTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueInvalidRuntimeConfigTestCase from ValueValidatorTestCase
 * @phpstan-type propertyPassesTestCase array{array, DtoInterface, string}
 * @phpstan-type propertyFailsTestCase array{array, DtoInterface, string, ?string, ?class-string<PropertyValidatorException>}
 * @phpstan-type propertyInvalidRuntimeConfigTestCase array{array, DtoInterface, string, ?string, ?class-string<PropertyValidatorException>}
 */
trait PropertyValueValidatorTestCaseTrait
{

    /**
     * @param mixed $value
     * @return DtoInterface
     */
    protected static function createDtoFromValue(mixed $value): DtoInterface
    {
        return new class($value) implements DtoInterface {
            public function __construct(
                protected readonly mixed $testProperty
            ) {
            }
        };
    }

    protected static function getDtoPropertyToValidate(): string
    {
        return 'testProperty';
    }

    /**
     * @param valuePassesTestCase[] $testCases
     * @return propertyPassesTestCase[]
     */
    public static function createPropertyPassesFromValuePasses(array $testCases): array
    {
        return array_map(
            /**
             * @param valuePassesTestCase $testCaseParams
             * @return propertyPassesTestCase
             */
            static function (array $testCaseParams): array {
                return [
                    $testCaseParams[0],
                    static::createDtoFromValue($testCaseParams[1]),
                    static::getDtoPropertyToValidate(),
                ];
            },
            $testCases
        );
    }

    /**
     * @param valueFailsTestCase[] $testCases
     * @return propertyFailsTestCase[]
     */
    public static function createPropertyFailsFromValueFails(array $testCases): array
    {
        return array_map(
            /**
             * @param valueFailsTestCase $testCaseParams
             * @return propertyFailsTestCase
             */
            static function (array $testCaseParams): array {
                return [
                    $testCaseParams[0],
                    static::createDtoFromValue($testCaseParams[1]),
                    static::getDtoPropertyToValidate(),
                    $testCaseParams[2] ?? null,
                    (static function(?string $exceptionType) {
                        if ($exceptionType !== null) {
                            return str_replace('ValueValidator', 'PropertyValidator', $exceptionType);
                        }

                        return null;
                    })($testCaseParams[3] ?? null),
                ];
            },
            $testCases
        );
    }

    /**
     * @param valueInvalidRuntimeConfigTestCase[] $testCases
     * @return propertyInvalidRuntimeConfigTestCase[]
     */
    public static function createPropertyInvalidConfigFromValueInvalidConfig(array $testCases): array
    {
        return array_map(
            /**
             * @param valueInvalidRuntimeConfigTestCase $testCaseParams
             * @return propertyInvalidRuntimeConfigTestCase
             */
            static function (array $testCaseParams): array {
                return [
                    $testCaseParams[0],
                    static::createDtoFromValue($testCaseParams[1]),
                    static::getDtoPropertyToValidate(),
                    $testCaseParams[2] ?? null,
                    (static function(?string $exceptionType) {
                        if ($exceptionType !== null) {
                            return str_replace('ValueValidator', 'PropertyValidator', $exceptionType);
                        }

                        return null;
                    })($testCaseParams[3] ?? null),
                ];
            },
            $testCases
        );
    }
}
