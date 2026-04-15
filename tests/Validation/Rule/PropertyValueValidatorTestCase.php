<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionObject;
use Throwable;

/**
 * @extends ValueValidatorTestCase<PropertyValidatorInterface&ValueValidatorInterface>
 * @phpstan-import-type propertyPassesTestCase from PropertyValueValidatorTestCaseTrait
 * @phpstan-import-type propertyFailsTestCase from PropertyValueValidatorTestCaseTrait
 * @phpstan-import-type propertyInvalidRuntimeConfigTestCase from PropertyValueValidatorTestCaseTrait
 */
abstract class PropertyValueValidatorTestCase extends ValueValidatorTestCase
{
    use PropertyValueValidatorTestCaseTrait;

    /**
     * @return propertyPassesTestCase[]
     */
    public static function propertyPassesValidationProvider(): array
    {
        return static::createPropertyPassesFromValuePasses(static::valuePassesValidationProvider());
    }

    #[DataProvider('propertyPassesValidationProvider')]
    public function testValidatePropertyPasses(
        array $validatorParams,
        DtoInterface $dto,
        string $property,
    ): void {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validateProperty((new ReflectionObject($dto))->getProperty($property), $dto);
    }

    /**
     * @return propertyFailsTestCase[]
     */
    public static function propertyFailsValidationProvider(): array
    {
        return static::createPropertyFailsFromValueFails(static::valueFailsValidationProvider());
    }

    #[DataProvider('propertyFailsValidationProvider')]
    public function testValidatePropertyFails(
        array $validatorParams,
        DtoInterface $dto,
        string $property,
        ?string $exceptionMessage = null,
        ?string $exceptionType = null,
    ): void {
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateProperty((new ReflectionObject($dto))->getProperty($property), $dto);

            $this->fail('PropertyValidatorException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals($property, $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }

            if ($exceptionType !== null) {
                $this->assertInstanceOf($exceptionType, $exception);
            }
        }
    }

    /**
     * @return propertyInvalidRuntimeConfigTestCase[]
     */
    public static function propertyWithInvalidConfigProvider(): array
    {
        return static::createPropertyInvalidConfigFromValueInvalidConfig(static::valueWithInvalidConfigProvider());
    }

    #[DataProvider('propertyWithInvalidConfigProvider')]
    public function testValidatePropertyWithInvalidConfig(
        array $validatorParams,
        DtoInterface $dto,
        string $property,
        ?string $exceptionMessage = null
    ): void {
        if (!static::$canHaveInvalidConfig) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateProperty((new ReflectionObject($dto))->getProperty($property), $dto);

            $this->fail('PropertyValidatorConfigException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);
            $this->assertInstanceOf(ConfigurationException::class, $exception);
            $this->assertSame($validator, $exception->getValidator());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }
}
