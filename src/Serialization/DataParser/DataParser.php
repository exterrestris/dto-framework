<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataParser;

use BackedEnum;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Error;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\FactoryInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Exception\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserPreprocessingException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\InvalidDataTypeException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\InvalidDataValueException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\NullValueException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\UnparsableDataTypeException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\ValueParserException;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\DataPreprocessorInterface;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\Exception\PreprocessorException;
use Exterrestris\DtoFramework\Serialization\Rule\NullIfEmpty;
use Exterrestris\DtoFramework\Serialization\Rule\UseDataPreprocessor;
use Exterrestris\DtoFramework\Serialization\Utility\GetPropertyMappingTrait;
use Exterrestris\DtoFramework\Utility\Exception\ParseDateException;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
use Exterrestris\DtoFramework\Utility\GetPropertyDateFormatTrait;
use Exterrestris\DtoFramework\Utility\ParseDateTrait;
use Psr\Log\LoggerInterface;
use ReflectionObject;
use ReflectionProperty;
use TypeError;
use ValueError;

/**
 * @template Dto of DtoInterface
 * @template ValueConverter of Closure(mixed $value): mixed
 */
class DataParser implements DataParserInterface
{
    use GetAttributeTrait;
    use GetPropertyMappingTrait;
    use GetPropertyDateFormatTrait;
    use ParseDateTrait;

    /**
     * @var array<class-string<DtoInterface>, array<string, string>>
     */
    private array $propertyMaps = [];

    /**
     * @var Closure
     */
    private array $valueConverters = [];

    /**
     * @var array<class-string<DtoInterface>, ?DataPreprocessorInterface>
     */
    private array $preprocessorCache = [];

    public function __construct(
        protected readonly FactoryInterface $dtoFactory,
        protected readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param class-string<Dto> $dtoType
     * @param object|array|null $dtoData
     * @param bool $skipFailures
     * @return ?DtoInterface
     * @throws DataParserException
     */
    protected function createDto(string $dtoType, object|array|null $dtoData, bool $skipFailures = false): ?DtoInterface
    {
        if ($dtoData === null) {
            return null;
        }

        try {
            try {
                return $this->dtoFactory->create($dtoType, $this->convertData($dtoData, $dtoType));
            } catch (TypeError $e) {
                throw new InvalidDataTypeException($e->getMessage(), previous: $e);
            }
        } catch (DataParserException $e) {
            $this->logger->error(
                sprintf('Unable to parse DTO data: %s', $e->getMessage()),
                [
                    'exception' => $e,
                    'dtoData' => json_encode($dtoData),
                ]
            );

            if ($skipFailures) {
                return null;
            }

            throw $e::withData($e, $dtoData);
        }
    }

    /**
     * @param ReflectionProperty $property
     * @return Closure
     * @throws ValueParserException
     */
    protected function getValueConverter(ReflectionProperty $property): Closure
    {
        $propertyType = $property->getType();

        if ($propertyType->isBuiltin()) {
            return static function (mixed $value) use ($propertyType): mixed {
                try {
                    if ($propertyType->getName() !== 'mixed') {
                        settype($value, $propertyType->getName());
                    }
                    return $value;
                } catch (Error $e) {
                    throw new InvalidDataTypeException($e->getMessage(), previous: $e);
                }
            };
        }

        if (is_a($propertyType->getName(), DtoInterface::class, true)) {
            $nullIfEmpty = $this->getAttribute($property, NullIfEmpty::class);

            return function (mixed $data) use ($propertyType, $nullIfEmpty): ?DtoInterface {
                $data = $this->preprocessData($data, $propertyType->getName());
                $dto = $this->createDto($propertyType->getName(), $data);

                if (!$nullIfEmpty) {
                    return $dto;
                }

                $reflect = new ReflectionObject($dto);

                foreach ($reflect->getProperties() as $property) {
                    if ($property->isInitialized($dto) && $property->getValue($dto) !== null) {
                        return $dto;
                    }
                }

                return null;
            };
        }

        if (is_a($propertyType->getName(), CollectionInterface::class, true)) {
            try {
                $collectionType = $this->getAttribute($property, CollectionType::class)?->getDtoType();
            } catch (InvalidTypeException $e) {
                throw new ValueParserException($e->getMessage(), previous: $e);
            }

            if ($collectionType) {
                return function (array|object|null $data) use ($collectionType): CollectionInterface {
                    return $this->createCollection(
                        $collectionType,
                        $this->preprocessData($data, $collectionType)
                    );
                };
            }
        }

        if (is_a($propertyType->getName(), DateTimeInterface::class, true)) {
            return function (string $value) use ($property): DateTimeImmutable {
                $dateFormat = $this->getDateFormat($property);

                try {
                    $date = $this->parseDate(
                        $value,
                        $dateFormat->getFormat(),
                        $dateFormat->getRoundingMode(),
                        $dateFormat->getRoundingUnit()
                    );
                } catch (ParseDateException $exception) {
                    throw new ValueParserException(
                        sprintf(
                            'Cannot parse value "%3$s" for DateTime property "%1$s" of "%2$s" using format "%4$s"',
                            $property->getName(),
                            $property->getDeclaringClass()->getName(),
                            $value,
                            $exception->getDateFormat()
                        ),
                        previous: $exception
                    );
                }

                return $date;
            };
        }

        if (is_a($propertyType->getName(), BackedEnum::class, true)) {
            return static function (int|string $value) use ($property, $propertyType): BackedEnum {
                try {
                    /** @noinspection PhpUndefinedMethodInspection */
                    return $propertyType->getName()::from($value);
                } catch (ValueError $e) {
                    throw new InvalidDataValueException(
                        sprintf(
                            'Cannot parse value "%3$s" for "%4$s" property "%1$s" of "%2$s"',
                            $property->getName(),
                            $property->getDeclaringClass()->getName(),
                            $value,
                            $propertyType->getName()
                        ),
                        previous: $e
                    );
                }
            };
        }

        throw new UnparsableDataTypeException(
            sprintf(
                'Cannot parse type "%3$s for "property "%1$s" of "%2$s"',
                $property->getName(),
                $property->getDeclaringClass()->getName(),
                $propertyType->getName(),
            )
        );
    }

    protected function getNow(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    /**
     * @param ReflectionProperty $property
     * @return Closure(mixed $value): mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    protected function getNullSafeValueConverter(ReflectionProperty $property): Closure
    {
        $valueConverter = $this->getValueConverter($property);

        return static function (mixed $value) use ($property, $valueConverter): mixed {
            if ($value === null) {
                if ($property->getType()->allowsNull()) {
                    return null;
                }

                throw new NullValueException(
                    sprintf(
                        'Cannot parse null value for non-nullable property "%s" of "%s"',
                        $property->getName(),
                        $property->getDeclaringClass()->getName()
                    )
                );
            }

            return $valueConverter($value);
        };
    }

    /**
     * @throws DataParserException
     */
    protected function createCollection(
        string $ofDtoType,
        array $collectionDtoData,
        bool $skipFailures = false
    ): CollectionInterface {
        $collection = $this->dtoFactory->createCollection($ofDtoType);

        if (!$collectionDtoData) {
            return $collection;
        }

        $collectionDtoData = array_map(function ($dtoData) use ($ofDtoType, $skipFailures) {
            return $this->createDto($ofDtoType, $dtoData, $skipFailures);
        }, $collectionDtoData);

        if ($skipFailures) {
            $collectionDtoData = array_values(array_filter($collectionDtoData));
        }

        return $collection->add(...$collectionDtoData);
    }

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return void
     */
    private function compileMappings(string $dtoType): void
    {
        $this->propertyMaps[$dtoType] = [];
        $this->valueConverters[$dtoType] = [];

        $reflect = new ReflectionObject($this->dtoFactory->create($dtoType));

        foreach ($reflect->getProperties() as $property) {
            if ($this->getAttribute($property, Internal::class)) {
                continue;
            }

            $this->propertyMaps[$dtoType][$this->mapNameFrom($property)] = $property->getName();
            $this->valueConverters[$dtoType][$this->mapNameFrom($property)] = $this->getNullSafeValueConverter(
                $property
            );
        }
    }

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return array<string,string>
     */
    private function getPropertyMap(string $dtoType): array
    {
        if (!isset($this->propertyMaps[$dtoType])) {
            $this->compileMappings($dtoType);
        }

        return $this->propertyMaps[$dtoType];
    }

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return Closure
     */
    private function getValueMappers(string $dtoType): array
    {
        if (!isset($this->valueConverters[$dtoType])) {
            $this->compileMappings($dtoType);
        }

        return $this->valueConverters[$dtoType];
    }

    /**
     * @param object|array|null $rawData
     * @param class-string<DtoInterface> $dtoType
     * @return ?array
     */
    protected function convertData(object|array|null $rawData, string $dtoType): ?array
    {
        if ($rawData === null) {
            return null;
        }

        $dtoData = [];
        $propertyMap = $this->getPropertyMap($dtoType);
        $valueMapper = $this->getValueMappers($dtoType);

        foreach ($rawData as $dataProperty => $dataValue) {
            if (!isset($propertyMap[$dataProperty])) {
                continue;
            }

            if (isset($valueMapper[$dataProperty])) {
                $dataValue = $valueMapper[$dataProperty]($dataValue);
            }

            $dtoData[$propertyMap[$dataProperty]] = $dataValue;
        }

        return $dtoData;
    }

    private function getDataPreprocessor(string $dtoType): ?DataPreprocessorInterface
    {
        if (!array_key_exists($dtoType, $this->preprocessorCache)) {
            $this->preprocessorCache[$dtoType] = $this->getAttribute(
                new ReflectionObject($this->dtoFactory->create($dtoType)),
                UseDataPreprocessor::class
            )?->getDataPreprocessor();
        }

        return $this->preprocessorCache[$dtoType];
    }

    protected function preprocessData(mixed $rawData, string $dtoType): object|array|null
    {
        if ($rawData === null) {
            return null;
        }

        try {
            return $this->getDataPreprocessor($dtoType)?->preprocess($rawData, $dtoType) ?? $rawData;
        } catch (PreprocessorException $e) {
            throw new DataParserPreprocessingException($e->getMessage(), previous: $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function parseInto(mixed $data, string $dtoType): DtoInterface|CollectionInterface|null
    {
        $data = $this->preprocessData($data, $dtoType);

        if (is_array($data) && array_is_list($data)) {
            return $this->createCollection($dtoType, $data);
        } else {
            return $this->createDto($dtoType, $data);
        }
    }

    /**
     * @inheritDoc
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function tryParseInto(mixed $data, string $dtoType): DtoInterface|CollectionInterface|null
    {
        $data = $this->preprocessData($data, $dtoType);

        if (is_array($data) && array_is_list($data)) {
            return $this->createCollection($dtoType, $data, true);
        } else {
            return $this->createDto($dtoType, $data, true);
        }
    }
}
