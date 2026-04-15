<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use DomainException;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Exception\DtoException;
use Exterrestris\DtoFramework\Utility\GetShortDtoTypeTrait;
use Throwable;

class InvalidDtoException extends DomainException implements ItemValidatorException, DtoException
{
    use GetShortDtoTypeTrait;

    /**
     * @param DtoInterface $invalidDto
     * @param DtoPropertyValidationException[] $validationExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly DtoInterface $invalidDto,
        private readonly array $validationExceptions,
        ?Throwable $previous = null
    ) {
        $invalidCount = count($this->getInvalidProperties());

        parent::__construct(
            sprintf(
                "%s %s %s invalid\n%s",
                $invalidCount,
                $this->getShortType($invalidDto::class),
                $invalidCount === 1 ? 'property is' : 'properties are',
                $this->compileMessages(),
            ),
            0,
            $previous
        );
    }

    private function compileMessages(): string
    {
        return implode("\n", array_map(
            static function (DtoPropertyValidationException $reason): string {
                return sprintf('- %s: %s', $reason->getProperty(), str_replace("\n", "\n  ", $reason->getMessage()));
            },
            $this->validationExceptions
        ));
    }

    public function getDto(): DtoInterface
    {
        return $this->invalidDto;
    }

    /**
     * @return string[]
     */
    public function getInvalidProperties(): array
    {
        return array_unique(array_map(
            static fn(DtoPropertyValidationException $exception): string => $exception->getProperty(),
            $this->validationExceptions,
        ));
    }

    /**
     * @return DtoPropertyValidationException[]
     */
    public function getValidationExceptions(): array
    {
        return $this->validationExceptions;
    }

    /**
     * @return DtoPropertyValidationException[]
     */
    public function getPropertyValidationExceptions(string $property): array
    {
        return array_values(array_filter(
            $this->validationExceptions,
            static fn(DtoPropertyValidationException $exception): bool => $exception->getProperty() === $property
        ));
    }
}
