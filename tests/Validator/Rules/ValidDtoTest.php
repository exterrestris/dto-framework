<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Tests\Mocks\TestDto;
use Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\Rules\ValidDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(ValidDto::class)]
class ValidDtoTest extends TestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                null,
            ],
            [
                new TestHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => null,
                ]),
            ],
            [
                new TestHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => new TestHierarchicalDto([
                        'name' => 'Jane Doe',
                    ]),
                ]),
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                0,
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
            ],
            [
                '',
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
            ],
            [
                [],
                'Value must be an instance of Exterrestris\DtoFramework\Dto\DtoInterface',
            ],
            [
                new TestHierarchicalDto([
                    'name' => 'John Doe',
                    'parent' => new TestHierarchicalDto([
                        'name' => '',
                    ]),
                ]),
                <<<'MESSAGE'
                1 Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto property is invalid
                - parent: 1 Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ],
        ];
    }

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = new ValidDto();

        $validator->validateProperty($value, new TestDto(), 'parent');
    }

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(mixed $value, ?string $exceptionMessage = null): void
    {
        $entity = new TestHierarchicalDto();
        $validator = new ValidDto();

        try {
            $validator->validateProperty($value, $entity, 'parent');
            $this->fail('Exception not thrown');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(PropertyValidatorException::class, $exception);

            $this->assertSame($validator, $exception->getValidator());
            $this->assertEquals('parent', $exception->getProperty());

            if ($exceptionMessage !== null) {
                $this->assertEquals($exceptionMessage, $exception->getMessage());
            }
        }
    }
}
