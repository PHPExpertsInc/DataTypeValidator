<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator\Tests;

use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\IsAStrictDataType */
class IsAStrictDataTypeTest extends TestCase
{
    /** @var IsAStrictDataType */
    private $isA;

    public function setUp(): void
    {
        $this->isA = new IsAStrictDataType();

        parent::setUp();
    }

    public function testWillReturnTrueForValidValues()
    {
        $strictTypePairs = DataTypesLists::getValidStrictDataAndTypes();

        foreach ($strictTypePairs as $idx => [$expectedType, $value]) {
            self::assertTrue($this->isA->isType($value, $expectedType), json_encode($value) . " ($idx) did not report as a(n) valid $expectedType.");
        }
    }

    public function testWillReturnFalseForInvalidValues()
    {
        $strictTypePairs = DataTypesLists::getInvalidStrictDataAndTypes();

        foreach ($strictTypePairs as $idx => [$expectedType, $value]) {
            self::assertFalse($this->isA->isType($value, $expectedType), json_encode($value) . " ($idx) did not report as a(n) invalid $expectedType.");
        }
    }

    public function testWillMatchShortClasses()
    {
        $object = new IsAStrictDataType();

        self::assertTrue($this->isA->isType($object, 'fuzzy', 'IsAStrictDataType'));
        self::assertFalse($this->isA->isType($object, 'fuzzy', 'DoesntExist'));
        self::assertFalse($this->isA->isType($object, 'fuzzy', IsAStrictDataType::class));
        self::assertFalse($this->isA->isType('string', 'fuzzy', 'IsAStrictDataType'));
    }

    public function testWillMatchSpecificClasses()
    {
        $object = new IsAStrictDataType();

        self::assertTrue($this->isA->isType($object, 'specific', IsAStrictDataType::class));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAStrictDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAStrictDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAStrictDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'DoesntExist'));
        self::assertFalse($this->isA->isType('string', 'specific', IsAStrictDataType::class));
    }
}
