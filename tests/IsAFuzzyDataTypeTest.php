<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator\Tests;

use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\IsAFuzzyDataType */
class IsAFuzzyDataTypeTest extends TestCase
{
    /** @var IsAFuzzyDataType */
    private $isA;

    public function setUp(): void
    {
        $this->isA = new IsAFuzzyDataType();

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
        $strictTypePairs = DataTypesLists::getInvalidFuzzyDataAndTypes();

        foreach ($strictTypePairs as $idx => [$expectedType, $value]) {
            self::assertFalse($this->isA->isType($value, $expectedType), json_encode($value) . " ($idx) did not report as a(n) invalid $expectedType.");
        }
    }

    public function testWillMatchShortClasses()
    {
        $object = new IsAFuzzyDataType();

        self::assertTrue($this->isA->isType($object, 'fuzzy', 'IsAFuzzyDataType'));
        self::assertFalse($this->isA->isType($object, 'fuzzy', 'DoesntExist'));
        self::assertFalse($this->isA->isType($object, 'fuzzy', IsAFuzzyDataType::class));
        self::assertFalse($this->isA->isType('string', 'fuzzy', 'IsAFuzzyDataType'));
    }

    public function testWillMatchSpecificClasses()
    {
        $object = new IsAFuzzyDataType();

        self::assertTrue($this->isA->isType($object, 'specific', IsAFuzzyDataType::class));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAFuzzyDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAFuzzyDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'IsAFuzzyDataType'));
        self::assertFalse($this->isA->isType($object, 'specific', 'DoesntExist'));
        self::assertFalse($this->isA->isType('string', 'specific', IsAFuzzyDataType::class));
    }
}
