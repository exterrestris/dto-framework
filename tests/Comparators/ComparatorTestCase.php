<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Comparators;

use Closure;
use Exterrestris\DtoFramework\Comparators\ComparatorInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

abstract class ComparatorTestCase extends TestCase
{
    abstract public static function compareProvider(): array;

    #[DataProvider('compareProvider')]
    public function testCompare(DtoInterface $a, DtoInterface $b, int $result): void
    {
        $comparator = $this->getComparator();

        $this->assertEquals($result, $comparator->compare($a, $b));
    }

    abstract public static function areEqualProvider(): array;

    #[DataProvider('areEqualProvider')]
    public function testAreEqual(DtoInterface $a, DtoInterface $b): void
    {
        $comparator = $this->getComparator();

        $this->assertTrue($comparator->areEqual($a, $b));
    }

    abstract public static function areNotEqualProvider(): array;

    #[DataProvider('areNotEqualProvider')]
    public function testAreNotEqual(DtoInterface $a, DtoInterface $b): void
    {
        $comparator = $this->getComparator();

        $this->assertFalse($comparator->areEqual($a, $b));
    }

    public static function couldMatchProvider(): array
    {
        return [
            [
                DtoInterface::class,
                true,
            ]
        ];
    }

    #[DataProvider('couldMatchProvider')]
    public function testCouldMatch(string $entityType, bool $match): void
    {
        $comparator = $this->getComparator();

        $this->assertEquals($match, $comparator->couldMatch($entityType));
    }

    abstract public static function generateClosureProvider(): array;

    #[DataProvider('generateClosureProvider')]
    public function testGenerateIsEqualToClosure(DtoInterface $entity, DtoInterface $equalTo, DtoInterface $notEqualTo): void
    {
        $comparator = $this->getComparator();
        $closure = $comparator->generateIsEqualToClosure($entity);

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertInstanceOf(Closure::class, $closure);
        $this->assertTrue($closure($equalTo));
        $this->assertFalse($closure($notEqualTo));
    }

    abstract protected function getComparator(): ComparatorInterface;
}
