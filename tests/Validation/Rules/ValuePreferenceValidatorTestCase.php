<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PreferenceValidatorException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\ValuePreferenceValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Throwable;

/**
 * @extends ValueValidatorTestCase<ValuePreferenceValidator>
 * @phpstan-import-type valuePassesTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueFailsTestCase from ValueValidatorTestCase
 * @phpstan-import-type strictnessTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueInvalidRuntimeConfigTestCase from ValueValidatorException
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
            $this->assertInstanceOf(PreferenceValidatorException::class, $exception);
            $this->assertSame($validator, $exception->getValidator());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    /**
     * @return valueInvalidRuntimeConfigTestCase[]
     */
    public static function valueWithInvalidPreferenceConfigProvider(): array
    {
        return static::valueWithInvalidConfigProvider();
    }

    #[DataProvider('valueWithInvalidPreferenceConfigProvider')]
    public function testValidateValuePreferenceWithInvalidConfig(array $validatorParams, mixed $value, ?string $exceptionMessage = null): void
    {
        if (!static::$canHaveInvalidConfig) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateValuePreference($value);

            $this->fail('ValueValidatorConfigException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(ValueValidatorException::class, $exception);
            $this->assertInstanceOf(PreferenceValidatorException::class, $exception);
            $this->assertInstanceOf(ConfigurationException::class, $exception);
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

        $this->expectException(ValueValidatorException::class);
        $validator->validateValuePreference($valueMeetingRequirement);
    }
}
