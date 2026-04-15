<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Rules;

use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @phpstan-type valuePassesTestCase array{array, mixed}
 * @phpstan-type valueFailsTestCase array{array, mixed, ?string}
 */
abstract class ValueValidatorTestCase extends TestCase
{
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

    abstract protected function getValidator(array $params): ValueValidator;
}
