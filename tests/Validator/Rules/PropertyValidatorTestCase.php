<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class PropertyValidatorTestCase extends TestCase
{
    /**
     * @return array{array, mixed}[]
     */
    abstract public static function passValidationProvider(): array;

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(array $validatorParams, mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validateProperty($value, static::getMockEntity(), 'test');
    }

    /**
     * @return array{array, mixed, ?string}[]
     */
    abstract public static function failValidationProvider(): array;

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(array $validatorParams, mixed $value, ?string $exceptionMessage = null): void
    {
        $entity = static::getMockEntity();
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

    protected static function getMockEntity(): DtoInterface
    {
        return new class() implements DtoInterface {};
    }

    abstract protected function getValidator(array $params): PropertyValidator;
}
