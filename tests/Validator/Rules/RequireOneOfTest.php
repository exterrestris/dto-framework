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
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Throwable;

#[CoversClass(RequireOneOf::class)]
#[Group('validation')]
#[Group('validator-rules')]
class RequireOneOfTest extends TestCase
{
    /**
     * @return array{DtoInterface, string, ?string}[]
     */
    public static function passValidationProvider(): array
    {
        return [
            [
                static::getMockDto()->setProperty1('test')->setAlternate1('value'),
                'property1',
            ],
            [
                static::getMockDto()->setProperty2('test')->setAlternate3('value'),
                'property1',
            ],
            [
                static::getMockDto()->setProperty1('test')->setAlternate1('value'),
                'property1',
            ],
            [
                static::getMockDto()->setProperty2('test')->setAlternate3('value'),
                'alternate1',
            ],
            [
                static::getMockDto()->setProperty1('test')->setAlternate1('value'),
                'alternate1',
            ],
            [
                static::getMockDto()->setProperty2('test')->setAlternate3('value'),
                'property1',
            ],
            [
                static::getMockDto()->setProperty1('test')->setAlternate1('value'),
                'alternate1',
            ],
            [
                static::getMockDto()->setProperty2('test')->setAlternate3('value'),
                'alternate1',
            ],
        ];
    }

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(DtoInterface $entity, string $entityProperty): void
    {
        $this->expectNotToPerformAssertions();

        $validator = $this->getValidator();

        $validator->validateProperty((new ReflectionObject($entity))->getProperty($entityProperty), $entity);
    }

    /**
     * @return array{DtoInterface, string, ?string}[]
     */
    public static function failValidationProvider(): array
    {
        return [
            [
                static::getMockDto(),
                'property1',
            ],
            [
                static::getMockDto()->setAlternate1('value'),
                'property1',
            ],
            [
                static::getMockDto(),
                'alternate1',
            ],
            [
                static::getMockDto()->setProperty1('test'),
                'alternate1',
            ],
        ];
    }

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(DtoInterface $entity, string $entityProperty): void
    {
        $validator = $this->getValidator();

        try {
            $validator->validateProperty((new ReflectionObject($entity))->getProperty($entityProperty), $entity);
            $this->fail('Exception not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);
            $this->assertInstanceOf(RequireOneOfValidationException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals($entityProperty, $exception->getProperty());
        }
    }

    protected static function getMockDto(): DtoInterface
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
