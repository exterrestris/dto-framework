<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\TestDto;
use Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validator\Rules\ValidCollection;
use Exterrestris\DtoFramework\Validator\Rules\ValidDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(ValidCollection::class)]
class ValidCollectionTest extends TestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                null,
            ],
            [
                new Collection(TestHierarchicalDto::class),
            ],
            [
                (new Collection(TestHierarchicalDto::class))->add(
                    new TestHierarchicalDto([
                        'name' => 'John Doe',
                        'children' => null,
                    ]),
                ),
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                0,
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                '',
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                [],
                'Value must be an instance of Exterrestris\DtoFramework\Dto\Collection\CollectionInterface',
            ],
            [
                (new Collection(TestHierarchicalDto::class))->add(
                    new TestHierarchicalDto([
                        'name' => 'John Doe',
                        'parent' => new TestHierarchicalDto([
                            'name' => '',
                        ]),
                    ]),
                ),
                <<<'MESSAGE'
                Exterrestris\DtoFramework\Tests\Mocks\TestHierarchicalDto 0: 1 property is invalid
                - parent: 1 property is invalid
                  - name: Value must not be empty
                MESSAGE,
            ],
        ];
    }

    #[DataProvider('passValidationProvider')]
    public function testValidatePasses(mixed $value): void
    {
        $this->expectNotToPerformAssertions();

        $validator = new ValidCollection();

        $validator->validateProperty($value, new TestDto(), 'parent');
    }

    #[DataProvider('failValidationProvider')]
    public function testValidateFails(mixed $value, ?string $exceptionMessage = null): void
    {
        $entity = new TestHierarchicalDto();
        $validator = new ValidCollection();

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
