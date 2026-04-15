<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Throwable;

abstract class DependentPropertyValidatorTestCase extends TestCase
{
    /**
     * @return array{array, mixed, mixed}[]
     */
    abstract public static function passValidationProvider(): array;

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(array $validatorParams, mixed $dependsOnValue, mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $dto = $this->getMockDto($value, $dependsOnValue);
        $validator = $this->getValidator($validatorParams);

        $validator->validateProperty(((new ReflectionObject($dto))->getProperty('property')), $dto);
    }

    /**
     * @return array{array, mixed, mixed, ?string}[]
     */
    abstract public static function failValidationProvider(): array;

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(
        array $validatorParams,
        mixed $dependsOnValue,
        mixed $value,
        ?string $exceptionMessage = null
    ): void {
        $dto = $this->getMockDto($value, $dependsOnValue);
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateProperty(((new ReflectionObject($dto))->getProperty('property')), $dto);
            $this->fail('Exception not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals('property', $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    protected function getMockDto(mixed $propertyValue, mixed $dependsOnValue): DtoInterface
    {
        return new class($propertyValue, $dependsOnValue) implements DtoInterface {

            public function __construct(
                protected mixed $property,
                protected mixed $dependsOn
            ) {
            }
        };
    }

    abstract protected function getValidator(array $params): PropertyValidatorInterface;
}
