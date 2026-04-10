<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
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

        $validator = $this->getValidator($validatorParams);

        $validator->validateProperty($value, $this->getMockEntity()->setDependsOn($dependsOnValue), 'test');
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
        $entity = $this->getMockEntity()->setDependsOn($dependsOnValue);
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validateProperty($value, $entity, 'test');
            $this->fail('Exception not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals('test', $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    protected function getMockEntity(): DtoInterface
    {
        return new class() implements DtoInterface {
            protected mixed $dependsOn;

            public function setDependsOn(mixed $dependsOn): static
            {
                $this->dependsOn = $dependsOn;
                return $this;
            }
        };
    }

    abstract protected function getValidator(array $params): PropertyValidator;
}
