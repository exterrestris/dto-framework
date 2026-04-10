<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\Exceptions\RequireOneOfValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\RequireOneOf;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(RequireOneOf::class)]
class RequireOneOfTest extends TestCase
{
    /**
     * @return array{DtoInterface, string, ?string}[]
     */
    public static function passValidationProvider(): array
    {
        return [
            [
                static::getMockEntity()->setProperty1('test')->setAlternate1('value'),
                'property1',
                null,
            ],
            [
                static::getMockEntity()->setProperty2('test')->setAlternate3('value'),
                'property1',
                null,
            ],
            [
                static::getMockEntity()->setProperty1('test')->setAlternate1('value'),
                'property1',
                'test',
            ],
            [
                static::getMockEntity()->setProperty2('test')->setAlternate3('value'),
                'alternate1',
                'test',
            ],
            [
                static::getMockEntity()->setProperty1('test')->setAlternate1('value'),
                'alternate1',
                null,
            ],
            [
                static::getMockEntity()->setProperty2('test')->setAlternate3('value'),
                'property1',
                null,
            ],
            [
                static::getMockEntity()->setProperty1('test')->setAlternate1('value'),
                'alternate1',
                'test',
            ],
            [
                static::getMockEntity()->setProperty2('test')->setAlternate3('value'),
                'alternate1',
                'test',
            ],
        ];
    }

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(DtoInterface $entity, string $entityProperty, ?string $value ): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator();

        $validator->validateProperty($value, $entity, $entityProperty);
    }

    /**
     * @return array{DtoInterface, string, ?string}[]
     */
    public static function failValidationProvider(): array
    {
        return [
            [
                static::getMockEntity(),
                'property1',
                null,
            ],
            [
                static::getMockEntity()->setAlternate1('value'),
                'property1',
                null,
            ],
            [
                static::getMockEntity(),
                'alternate1',
                null,
            ],
            [
                static::getMockEntity()->setProperty1('test'),
                'alternate1',
                null,
            ],
        ];
    }

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(DtoInterface $entity, string $entityProperty, ?string $value): void
    {
        $validator = $this->getValidator();

        try {
            $validator->validateProperty($value, $entity, $entityProperty);
            $this->fail('Exception not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);
            $this->assertInstanceOf(RequireOneOfValidationException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals($entityProperty, $exception->getProperty());
        }
    }

    protected static function getMockEntity(): DtoInterface
    {
        return new class() implements DtoInterface {
            #[RequireOneOf]
            protected ?string $property1 = null;
            #[RequireOneOf]
            protected ?string $property2 = null;
            #[RequireOneOf]
            protected ?string $property3 = null;
            #[RequireOneOf]
            protected ?string $property4 = null;
            #[RequireOneOf('alt')]
            protected ?string $alternate1 = null;
            #[RequireOneOf('alt')]
            protected ?string $alternate2 = null;
            #[RequireOneOf('alt')]
            protected ?string $alternate3 = null;
            #[RequireOneOf('alt')]
            protected ?string $alternate4 = null;

            public function setProperty1(?string $property1): static
            {
                $this->property1 = $property1;
                return $this;
            }

            public function setProperty2(?string $property2): static
            {
                $this->property2 = $property2;
                return $this;
            }

            public function setProperty3(?string $property3): static
            {
                $this->property3 = $property3;
                return $this;
            }

            public function setProperty4(?string $property4): static
            {
                $this->property4 = $property4;
                return $this;
            }
            public function setAlternate1(?string $alternate1): static
            {
                $this->alternate1 = $alternate1;
                return $this;
            }

            public function setAlternate2(?string $alternate2): static
            {
                $this->alternate2 = $alternate2;
                return $this;
            }

            public function setAlternate3(?string $alternate3): static
            {
                $this->alternate3 = $alternate3;
                return $this;
            }

            public function setAlternate4(?string $alternate4): static
            {
                $this->alternate4 = $alternate4;
                return $this;
            }
        };
    }

    protected function getValidator(): PropertyValidator
    {
        return new RequireOneOf();
    }
}
