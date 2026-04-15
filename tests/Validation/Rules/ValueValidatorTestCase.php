<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\Validators\Metadata\ConfigCannotBeInvalid;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Throwable;

/**
 * @template Validator of ValueValidator
 * @phpstan-type valuePassesTestCase array{array, mixed}
 * @phpstan-type valueFailsTestCase array{array, mixed, ?string}
 * @phpstan-type valueInvalidConfigTestCase array{array, ?string}
 * @phpstan-type valueInvalidRuntimeConfigTestCase array{array, mixed, ?string}
 */
abstract class ValueValidatorTestCase extends TestCase
{
    /**
     * @var class-string<Validator>
     */
    protected static string $validatorClass;
    protected static bool $canHaveInvalidConfig;

    public static function setUpBeforeClass(): void
    {
        $reflectTestCase = new ReflectionClass(static::class);

        static::$validatorClass = $reflectTestCase->getAttributes(CoversClass::class)[0]->newInstance()->className();

        $reflectValidator = new ReflectionClass(static::$validatorClass);
        $hasConfig = (bool) $reflectValidator->getConstructor()?->getNumberOfParameters();
        $invalidConfig = (bool) $reflectValidator->getAttributes(ConfigCannotBeInvalid::class);

        static::$canHaveInvalidConfig = $hasConfig && !$invalidConfig;
    }

    /**
     * @return valuePassesTestCase[]
     */
    abstract public static function valuePassesValidationProvider(): array;

    #[DataProvider('valuePassesValidationProvider')]
    public function testValidateValuePasses(array $validatorParams, mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validateValue($value);
    }

    /**
     * @return valueFailsTestCase[]
     */
    abstract public static function valueFailsValidationProvider(): array;

    #[DataProvider('valueFailsValidationProvider')]
    public function testValidateValueFails(array $validatorParams, mixed $value, ?string $exceptionMessage = null): void
    {
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateValue($value);

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
     * @return valueInvalidRuntimeConfigTestCase[]
     */
    public static function valueWithInvalidConfigProvider(): array
    {
        return [[[], null]];
    }

    #[DataProvider('valueWithInvalidConfigProvider')]
    public function testValidateValueWithInvalidConfig(array $validatorParams, mixed $value, ?string $exceptionMessage = null): void
    {
        if (!static::$canHaveInvalidConfig) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateValue($value);

            $this->fail('ValueValidatorConfigException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(ValueValidatorException::class, $exception);
            $this->assertInstanceOf(ConfigurationException::class, $exception);
            $this->assertSame($validator, $exception->getValidator());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    /**
     * @param array $validatorParams
     * @return Validator
     */
    protected function getValidator(array $validatorParams): ValueValidator
    {
        return new static::$validatorClass(...$validatorParams);
    }
}
