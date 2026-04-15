<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetShortDtoTypeTrait;
use Throwable;

class InvalidDtoException extends DomainException implements DtoValidationException
{
    use GetShortDtoTypeTrait;

    /**
     * @var array<string, DtoPropertyValidationException[]>
     */
    private readonly array $validationExceptions;

    /**
     * @param array<string, DtoPropertyValidationException[]> $validationExceptions
     */
    public function __construct(
        private readonly DtoInterface $dto,
        array $validationExceptions,
        ?Throwable $previous = null
    ) {
        $this->validationExceptions = array_filter($validationExceptions);

        parent::__construct(
            sprintf(
                "%s %s %s invalid\n%s",
                count($this->validationExceptions),
                $this->getShortType($this->dto::class),
                count($this->validationExceptions) === 1 ? 'property is' : 'properties are',
                $this->compileMessages(),
            ),
            0,
            $previous
        );
    }

    private function compileMessages(): string
    {
        return implode("\n", array_map(static function (array $reasons, string $property): string {
            return implode("\n", array_map(static function (DtoPropertyValidationException $reason) use ($property): string {
                return sprintf('- %s: %s', $property, str_replace("\n", "\n  ", $reason->getMessage()));
            }, array_unique($reasons)));
        }, $this->validationExceptions, array_keys($this->validationExceptions)));
    }

    public function getInvalidDto(): DtoInterface
    {
        return $this->dto;
    }

    /**
     * @return string[]
     */
    public function getInvalidProperties(): array
    {
        return array_keys($this->validationExceptions);
    }

    /**
     * @return array<string, DtoPropertyValidationException[]>
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
        return $this->validationExceptions[$property] ?? [];
    }
}
