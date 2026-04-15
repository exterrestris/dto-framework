<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Dto;

use DateTime;
use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\DtoException;
use Exterrestris\DtoFramework\Dto\Exceptions\InternalPropertyException;
use Exterrestris\DtoFramework\Dto\Exceptions\InvalidDataException;
use Exterrestris\DtoFramework\Dto\Exceptions\NoSuchPropertyException;
use Exterrestris\DtoFramework\Dto\Metadata\Internal;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractDto::class)]
class AbstractDtoTest extends TestCase
{
    public function testConstructor(): void
    {
        $dto = $this->getDto([
            'name' => 'John Doe',
            'date' => new DateTime('2026-04-08'),
        ]);

        $this->assertEquals('John Doe', $dto->getName());
        $this->assertEquals(new DateTime('2026-04-08'), $dto->getDate());
    }

    public static function invalidDataProvider(): array
    {
        return [
            [
                [
                    'John Doe',
                ],
                InvalidDataException::class,
            ],
            [
                [
                    'test' => 'value',
                ],
                NoSuchPropertyException::class,
            ],
            [
                [
                    'internal' => 'value',
                ],
                InternalPropertyException::class,
            ],
        ];
    }

    /**
     * @param array $data
     * @param class-string<DtoException> $expectedException
     * @return void
     */
    #[DataProvider('invalidDataProvider')]
    public function testConstructorThrows(array $data, string $expectedException): void
    {
        $this->expectException($expectedException);

        $this->getDto($data);
    }

    public function testClone(): void
    {
        $dto = $this->getDto([
            'name' => 'John Doe',
            'date' => new DateTime('2026-04-08'),
            'child' => $this->getDto([
                'name' => 'Jane Doe',
                'date' => new DateTime('2026-04-09'),
            ])
        ]);

        $clone = clone $dto;

        $this->assertNotSame($dto, $clone);
        $this->assertNotSame($dto->getDate(), $clone->getDate());
        $this->assertNotSame($dto->getChild(), $clone->getChild());
        $this->assertEquals($dto->getName(), $clone->getName());
        $this->assertEquals($dto->getDate(), $clone->getDate());
        $this->assertEquals($dto->getChild()->getName(), $clone->getChild()->getName());
        $this->assertEquals($dto->getChild()->getDate(), $clone->getChild()->getDate());
    }

    public function testWith(): void
    {
        $dto = $this->getDto([]);


        $new = $dto->with('name', 'John Doe');

        $this->assertNotSame($dto, $new);
        $this->assertEquals('John Doe', $new->getName());
        $this->assertNull($new->getDate());

        $new = $dto->with([
            'name' => 'Jane Doe',
            'date' => new DateTime('2026-04-08'),
        ]);

        $this->assertNotSame($dto, $new);
        $this->assertEquals('Jane Doe', $new->getName());
        $this->assertEquals(new DateTime('2026-04-08'), $new->getDate());
    }

    /**
     * @param array $data
     * @param class-string<DtoException> $expectedException
     * @return void
     */
    #[DataProvider('invalidDataProvider')]
    public function testWithThrows(array $data, string $expectedException): void
    {
        $dto = $this->getDto([]);

        $this->expectException($expectedException);

        $dto->with($data);
    }

    public function getDto(array $data): AbstractDto
    {
        return new class($data) extends AbstractDto {
            protected string $name;
            protected ?DateTimeInterface $date = null;
            #[Internal]
            protected ?string $internal = null;
            protected ?DtoInterface $child = null;
            protected ?DtoInterface $unintialized;

            public function getName(): string
            {
                return $this->name;
            }

            public function getDate(): ?DateTimeInterface
            {
                return $this->date;
            }

            public function getChild(): ?DtoInterface
            {
                return $this->child;
            }

            /** @noinspection PhpHierarchyChecksInspection */
            public function with(array|string $newData, mixed $newValue = null): static
            {
                return parent::with($newData, $newValue);
            }

            public function getUnintialized(): ?DtoInterface
            {
                return $this->unintialized;
            }
        };
    }
}
