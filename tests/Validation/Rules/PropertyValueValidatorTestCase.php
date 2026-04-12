<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use ReflectionProperty;
use Throwable;

/**
 * @phpstan-import-type valuePassesTestCase from ValueValidatorTestCase
 * @phpstan-import-type valueFailsTestCase from ValueValidatorTestCase
 * @phpstan-type propertyPassesTestCase array{array, ReflectionProperty, DtoInterface}
 * @phpstan-type propertyFailsTestCase array{array, ReflectionProperty, DtoInterface, ?string}
 */
abstract class PropertyValueValidatorTestCase extends ValueValidatorTestCase
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
     * @return propertyPassesTestCase[]
     */
    public static function propertyPassesValidationProvider(): array
    {
        return array_map(
            /**
             * @param valuePassesTestCase $testCaseParams
             * @return propertyPassesTestCase
             */
            static function (array $testCaseParams): array {
                $dto = static::createDtoFromValue($testCaseParams[1]);

                return array(
                    $testCaseParams[0],
                    (new ReflectionObject($dto))->getProperty(static::getDtoPropertyToValidate()),
                    $dto
                );
            },
            static::valuePassesValidationProvider()
        );
    }

    #[DataProvider('propertyPassesValidationProvider')]
    public function testValidatePropertyPasses(
        array $validatorParams,
        ReflectionProperty $property,
        DtoInterface $dto
    ): void {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validateProperty($property, $dto);
    }

    /**
     * @return propertyFailsTestCase[]
     */
    public static function propertyFailsValidationProvider(): array
    {
        return array_map(
            /**
             * @param valueFailsTestCase $testCaseParams
             * @return propertyFailsTestCase
             */
            static function (array $testCaseParams): array {
                $dto = static::createDtoFromValue($testCaseParams[1]);

                return array(
                    $testCaseParams[0],
                    (new ReflectionObject($dto))->getProperty(static::getDtoPropertyToValidate()),
                    $dto,
                    $testCaseParams[2] ?? null
                );
            },
            static::valueFailsValidationProvider()
        );
    }

    #[DataProvider('propertyFailsValidationProvider')]
    public function testValidatePropertyFails(
        array $validatorParams,
        ReflectionProperty $property,
        DtoInterface $dto,
        ?string $exceptionMessage = null
    ): void {
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateProperty($property, $dto);

            $this->fail('PropertyValidatorException not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals($property->getName(), $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    abstract protected function getValidator(array $params): PropertyValidator&ValueValidator;
}
