<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Validator\Exceptions\PreferenceValidatorException;
use Exterrestris\DtoFramework\Validator\PreferenceValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Throwable;

abstract class PreferenceValidatorTestCase extends PropertyValidatorTestCase
{
    /**
     * @return array{array, mixed}[]
     */
    abstract public static function passPreferenceValidationProvider(): array;

    #[DataProvider('passPreferenceValidationProvider')]
    public function testPreferenceValidatePasses(array $validatorParams, mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator($validatorParams);

        $validator->validatePreference($value, $this->getMockEntity(), 'test');
    }

    /**
     * @return array{array, mixed}[]
     */
    abstract public static function failPreferenceValidationProvider(): array;

    #[DataProvider('failPreferenceValidationProvider')]
    public function testPreferenceValidateFails(array $validatorParams, mixed $value, ?string $exceptionMessage = null): void
    {
        $entity = $this->getMockEntity();
        $validator = $this->getValidator($validatorParams);

        try {
            $validator->validatePreference($value, $entity, 'test');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PreferenceValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals('test', $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }

    abstract protected function getValidator(array $params): PreferenceValidator;
}
