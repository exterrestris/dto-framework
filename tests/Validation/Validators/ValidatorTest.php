<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validation\Validators;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidCollectionDtoException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidCollectionException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoPropertyException;
use Exterrestris\DtoFramework\Validation\Exceptions\MissingRequiredDtoPropertyException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Rules\MatchRegex;
use Exterrestris\DtoFramework\Validation\Rules\StringMaxLength;
use Exterrestris\DtoFramework\Validation\Rules\StringMaxLengthPreference;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validation\Validators\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[Group('validation')]
#[CoversClass(Validator::class)]
#[UsesClass(Collection::class)]
#[UsesClass(CollectionType::class)]
#[UsesClass(AbstractPropertyValueValidator::class)]
#[UsesClass(MatchRegex::class)]
#[UsesClass(StringMaxLength::class)]
#[UsesClass(StringMaxLengthPreference::class)]
#[UsesClass(InvalidCollectionDtoException::class)]
#[UsesClass(InvalidCollectionException::class)]
#[UsesClass(InvalidDtoException::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(MissingRequiredDtoPropertyException::class)]
#[UsesClass(ValueValidationException::class)]
#[UsesClass(ValueException::class)]
class ValidatorTest extends TestCase
{
    public static function validSerializableProvider(): array
    {
        return [
            [
                (new MockDto())
                    ->setName('very very long name')
                    ->setUninitialized('init')
                    ->setDate(new \DateTimeImmutable('2026-01-01')),
                false,
            ],
            [
                (new MockDto())
                    ->setName('long name')
                    ->setUninitialized('init'),
                true,
            ],
            [
                (new Collection(MockDto::class))
                    ->add(
                        (new MockDto())
                            ->setName('very very long name')
                            ->setUninitialized('init')
                    ),
                false,
            ],
            [
                (new Collection(MockDto::class))
                    ->add(
                        (new MockDto())
                            ->setName('long name')
                            ->setUninitialized('init')
                    ),
                true,
            ],
            [
                (new Collection(MockDto::class))
                    ->add(
                        (new MockDto())
                            ->setName('very very long name')
                            ->setUninitialized('init')
                    )
                    ->add(
                        (new MockDto())
                            ->setName('another very long name')
                            ->setUninitialized('init')
                    ),
                false,
            ],
            [
                (new Collection(MockDto::class))
                    ->add(
                        (new MockDto())
                            ->setName('long name')
                            ->setUninitialized('init')
                    )
                    ->add(
                        (new MockDto())
                            ->setName('another name')
                            ->setUninitialized('init')
                    ),
                true,
            ],
        ];
    }

    #[DataProvider('validSerializableProvider')]
    public function testValidateValidSerializable(DtoInterface|CollectionInterface $item, bool $enforcePreferences)
    {
        $validator = new Validator();

        $this->assertSame($item, $validator->validate($item, $enforcePreferences));
    }

    public static function invalidDtoProvider(): array
    {
        return [
            [
                (new MockDto())
                    ->setName('very very very very long name')
                    ->setUninitialized('init'),
                false,
                [
                    'name' => [
                        StringMaxLength::class,
                    ],
                ],
                [],
            ],
            [
                (new MockDto())
                    ->setName('very + very long name')
                    ->setUninitialized('init'),
                true,
                [
                    'name' => [
                        StringMaxLength::class,
                        MatchRegex::class,
                    ],
                ],
                [],
            ],
            [
                (new MockDto())
                    ->setName('very very long name')
                    ->setTitle('0'),
                true,
                [
                    'name' => [
                        StringMaxLength::class,
                    ],
                    'title' => [
                        MatchRegex::class,
                    ]
                ],
                [
                    'uninitialized',
                ],
            ],
            [
                (new MockDto())->setName('long name'),
                true,
                [],
                [
                    'uninitialized',
                ],
            ],
            [
                (new MockDto())->setChildren(new Collection(MockDtoInterface::class)),
                true,
                [
                    'children' => [
                        CollectionType::class,
                    ],
                ],
                [
                    'name',
                    'uninitialized',
                ],
            ],
        ];
    }

    /**
     * @param DtoInterface $dto
     * @param bool $enforcePreferences
     * @param array<string, class-string<PropertyValidator>[]> $expectedInvalidProperties
     * @param string[] $expectedRequiredProperties
     * @return void
     */
    #[DataProvider('invalidDtoProvider')]
    public function testValidateInvalidDto(
        DtoInterface $dto,
        bool $enforcePreferences,
        array $expectedInvalidProperties,
        array $expectedRequiredProperties
    ) {
        $validator = new Validator();

        try {
            $validator->validate($dto, $enforcePreferences);

            $this->fail('InvalidDtoException not thrown');
        } catch (InvalidDtoException $e) {
            $this->assertSame($dto, $e->getDto());

            $invalidProperties = $e->getInvalidProperties();

            $this->assertIsArray($invalidProperties);
            $this->assertCount(
                count($expectedInvalidProperties) + count($expectedRequiredProperties),
                $invalidProperties
            );

            foreach ($expectedRequiredProperties as $property) {
                $this->assertContains($property, $invalidProperties);

                $validationExceptions = $e->getPropertyValidationExceptions($property);

                $this->assertIsArray($validationExceptions);
                $this->assertCount(1, $validationExceptions);
                $this->assertContainsOnlyInstancesOf(MissingRequiredDtoPropertyException::class, $validationExceptions);
            }

            foreach ($expectedInvalidProperties as $property => $expectedValidators) {
                $this->assertContains($property, $invalidProperties);

                $validationExceptions = $e->getPropertyValidationExceptions($property);

                $this->assertIsArray($validationExceptions);
                $this->assertCount(count($expectedValidators), $validationExceptions);
                $this->assertContainsOnlyInstancesOf(InvalidDtoPropertyException::class, $validationExceptions);

                foreach ($expectedValidators as $i => $expectedValidator) {
                    $this->assertInstanceOf($expectedValidator, $validationExceptions[$i]->getValidator());
                }
            }
        }
    }

    public static function invalidCollectionProvider(): array
    {
        return [
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setName('very very very very long name')->setUninitialized('init')),
                false,
                [
                    0 => [
                        [
                            'name' => [
                                StringMaxLength::class,
                            ],
                        ],
                        [],
                    ]
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setName('long name')->setUninitialized('init'))
                    ->add((new MockDto())->setName('very very very very long name')->setUninitialized('init')),
                false,
                [
                    1 => [
                        [
                            'name' => [
                                StringMaxLength::class,
                            ],
                        ],
                        [],
                    ]
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setName('very + very long name')->setUninitialized('init')),
                true,
                [
                    0 => [
                        [
                            'name' => [
                                StringMaxLength::class,
                                MatchRegex::class,
                            ],
                        ],
                        [],
                    ],
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setName('very very long name')),
                true,
                [
                    0 => [
                        [
                            'name' => [
                                StringMaxLength::class,
                            ],
                        ],
                        [
                            'uninitialized',
                        ],
                    ]
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setName('long name')),
                true,
                [
                    0 => [
                        [],
                        [
                            'uninitialized',
                        ],
                    ],
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setChildren(new Collection(MockDtoInterface::class))),
                true,
                [
                    0 => [
                        [
                            'children' => [
                                CollectionType::class,
                            ],
                        ],
                        [
                            'name',
                            'uninitialized',
                        ],
                    ],
                ],
            ],
            [
                (new Collection(MockDto::class))
                    ->add((new MockDto())->setChildren(new Collection(MockDtoInterface::class)))
                    ->add(
                        (new MockDto())
                            ->setChildren(new Collection(MockDto::class))
                            ->setName('long name')
                            ->setUninitialized('init')
                    )
                    ->add(
                        (new MockDto())
                            ->setChildren(new Collection(MockDtoInterface::class))
                            ->setName('name')
                    ),
                true,
                [
                    0 => [
                        [
                            'children' => [
                                CollectionType::class,
                            ],
                        ],
                        [
                            'name',
                            'uninitialized',
                        ],
                    ],
                    2 => [
                        [
                            'children' => [
                                CollectionType::class,
                            ],
                        ],
                        [
                            'uninitialized',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param CollectionInterface $collection
     * @param bool $enforcePreferences
     * @param array<int, array{array<string, class-string<PropertyValidator>[]>, string[]}> $expectedInvalidDtos
     * @return void
     */
    #[DataProvider('invalidCollectionProvider')]
    public function testValidateInvalidCollection(
        CollectionInterface $collection,
        bool $enforcePreferences,
        array $expectedInvalidDtos,
    ) {
        $validator = new Validator();

        try {
            $validator->validate($collection, $enforcePreferences);

            $this->fail('InvalidDtoException not thrown');
        } catch (InvalidCollectionException $e) {
            $this->assertSame($collection, $e->getCollection());

            $invalidDtoExceptions = array_combine(
                array_map(static fn(InvalidCollectionDtoException $e) => $e->getIndex(), $e->getInvalidDtoExceptions()),
                $e->getInvalidDtoExceptions()
            );

            $this->assertCount(count($expectedInvalidDtos), $invalidDtoExceptions);

            foreach ($invalidDtoExceptions as $dtoIndex => $invalidDtoException) {
                $this->assertSame($collection->get($dtoIndex), $invalidDtoException->getDto());

                list($expectedInvalidProperties, $expectedRequiredProperties) = $expectedInvalidDtos[$dtoIndex];

                $invalidProperties = $invalidDtoException->getInvalidProperties();

                $this->assertIsArray($invalidProperties);
                $this->assertCount(
                    count($expectedInvalidProperties) + count($expectedRequiredProperties),
                    $invalidProperties
                );

                foreach ($expectedRequiredProperties as $property) {
                    $this->assertContains($property, $invalidProperties);

                    $validationExceptions = $invalidDtoException->getPropertyValidationExceptions($property);

                    $this->assertIsArray($validationExceptions);
                    $this->assertCount(1, $validationExceptions);
                    $this->assertContainsOnlyInstancesOf(MissingRequiredDtoPropertyException::class, $validationExceptions);
                }

                foreach ($expectedInvalidProperties as $property => $expectedValidators) {
                    $this->assertContains($property, $invalidProperties);

                    $validationExceptions = $invalidDtoException->getPropertyValidationExceptions($property);

                    $this->assertIsArray($validationExceptions);
                    $this->assertCount(count($expectedValidators), $validationExceptions);
                    $this->assertContainsOnlyInstancesOf(InvalidDtoPropertyException::class, $validationExceptions);

                    foreach ($expectedValidators as $i => $expectedValidator) {
                        $this->assertInstanceOf($expectedValidator, $validationExceptions[$i]->getValidator());
                    }
                }
            }
        }
    }
}
