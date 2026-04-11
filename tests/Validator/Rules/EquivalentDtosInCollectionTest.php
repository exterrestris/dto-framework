<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\Collection\AbstractCollection;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\EquivalentDtosInCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[Group('validation')]
#[Group('validator-rules')]
#[CoversClass(EquivalentDtosInCollection::class)]
#[UsesClass(PropertyValidationException::class)]
#[UsesClass(ValueException::class)]
#[UsesClass(AbstractCollection::class)]
#[UsesClass(Collection::class)]
class EquivalentDtosInCollectionTest extends DependentPropertyValidatorTestCase
{
    public static function passValidationProvider(): array
    {
        return [
            [
                ['property', null, NullDependentValue::PassIfNull],
                null,
                null,
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                null,
                null,
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                null,
                null,
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
        ];
    }

    public static function failValidationProvider(): array
    {
        return [
            [
                ['property', null, NullDependentValue::FailIfNull],
                null,
                null,
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                null,
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()->add(
                    static::getMockCollectionDto()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()->add(
                    static::getMockCollectionDto()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()->add(
                    static::getMockCollectionDto()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')),
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockCollection()
                    ->add(static::getMockCollectionDto()->setProperty('value'))
                    ->add(static::getMockCollectionDto()->setProperty('value2')),
                static::getMockCollection()->add(static::getMockCollectionDto()->setAlternate('value')),
            ],


        ];
    }

    protected static function getMockCollectionDto(): DtoInterface
    {
        return new class() implements DtoInterface {
            protected string $property;
            protected string $alternate;

            public function setProperty(string $property): static
            {
                $this->property = $property;
                return $this;
            }

            public function setAlternate(string $alternate): static
            {
                $this->alternate = $alternate;
                return $this;
            }
        };
    }

    protected static function getMockCollection(): CollectionInterface
    {
        return new class() extends Collection {
            protected readonly string $dtoType;
            protected readonly array $items;

            /** @noinspection PhpMissingParentConstructorInspection */
            public function __construct(array $items = [])
            {
                $this->dtoType = DtoInterface::class;
                $this->items = $items;
            }

            protected function newCollection(array $items = []): CollectionInterface
            {
                return new static($items);
            }
        };
    }

    protected function getValidator(array $params): PropertyValidator
    {
        return new EquivalentDtosInCollection('dependsOn', ...$params);
    }
}
