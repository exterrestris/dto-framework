<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\Exceptions\PreferenceValidatorException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validator\ValuePreferenceValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Throwable;

/**
 * @phpstan-type valuePassesTestCase array{array, mixed}
 * @phpstan-type valueFailsTestCase array{array, mixed, ?string}
 * @phpstan-type strictnessTestCase array{array, mixed, mixed}
 */
abstract class ValuePreferenceValidatorTestCase extends ValueValidatorTestCase
{
    /**
     * @return valuePassesTestCase[]
     */
    abstract public static function valuePassesPreferenceValidationProvider(): array;

    #[DataProvider('valuePassesPreferenceValidationProvider')]
    public function testValidateValuePreferencePasses(array $validatorParams, mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validateValuePreference($value);
    }

    /**
     * @return valueFailsTestCase[]
     */
    abstract public static function valueFailsPreferenceValidationProvider(): array;

    #[DataProvider('valueFailsPreferenceValidationProvider')]
    public function testValidateValuePreferenceFails(
        array $validatorParams,
        mixed $value,
        ?string $exceptionMessage = null
    ): void {
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateValuePreference($value);

            $this->fail('ValueValidatorException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(ValueValidatorException::class, $exception);
            $this->assertSame($validator, $exception->getValidator());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    /**
     * @return strictnessTestCase[]
     */
    abstract public static function strictnessProvider(): array;

    #[DataProvider('strictnessProvider')]
    public function testPreferenceStricterThanRequirement(
        array $validatorParams,
        mixed $valueMeetingPreference,
        mixed $valueMeetingRequirement,
    ): void {
        $validator = $this->getValidator($validatorParams);

        $validator->validateValuePreference($valueMeetingPreference);
        $validator->validateValue($valueMeetingPreference);
        $validator->validateValue($valueMeetingRequirement);

        $this->expectException(PreferenceValidatorException::class);
        $validator->validateValuePreference($valueMeetingRequirement);
    }

    abstract protected function getValidator(array $params): ValuePreferenceValidator;
}
