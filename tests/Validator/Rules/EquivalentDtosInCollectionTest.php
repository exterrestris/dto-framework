<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Validator\Rules;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\Rules\Configuration\NullDependentValueBehaviour as NullDependentValue;
use Exterrestris\DtoFramework\Validator\Rules\EquivalentDtosInCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(EquivalentDtosInCollection::class)]
#[Group('validation')]
#[Group('validator-rules')]
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
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                null,
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
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
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                null,
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                null,
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                null,
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()->add(
                    static::getMockCollectionEntity()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()->add(
                    static::getMockCollectionEntity()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()->add(
                    static::getMockCollectionEntity()
                        ->setProperty('value')
                        ->setAlternate('value2')
                ),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')),
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value2')->setAlternate('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value3')->setAlternate('value2')),
            ],
            [
                ['property', null, NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', null, NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setProperty('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::PassIfValueIsNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
            ],
            [
                ['property', 'alternate', NullDependentValue::FailIfNull],
                static::getMockEntityCollection()
                    ->add(static::getMockCollectionEntity()->setProperty('value'))
                    ->add(static::getMockCollectionEntity()->setProperty('value2')),
                static::getMockEntityCollection()->add(static::getMockCollectionEntity()->setAlternate('value')),
            ],


        ];
    }

    protected static function getMockCollectionEntity(): DtoInterface
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

    protected static function getMockEntityCollection(): CollectionInterface
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
