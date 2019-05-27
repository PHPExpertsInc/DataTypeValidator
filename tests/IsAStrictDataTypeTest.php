<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/DataTypeValidator
 *
 * This file is licensed under the MIT License.
 */

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

        self::assertTrue($this->isA->isType($object, 'IsAStrictDataType'));
        self::assertFalse($this->isA->isType($object, 'DoesntExist'));
        self::assertFalse($this->isA->isType('string', 'IsAStrictDataType'));
        self::assertTrue($this->isA->isFuzzyObject($object, 'IsAStrictDataType'));
        self::assertFalse($this->isA->isFuzzyObject($object, IsAStrictDataType::class));
    }

    public function testWillMatchSpecificClasses()
    {
        $object = new IsAStrictDataType();

        self::assertTrue($this->isA->isType($object,  IsAStrictDataType::class));
        self::assertFalse($this->isA->isType($object, 'DoesntExist'));
        self::assertFalse($this->isA->isSpecificObject('string', IsAStrictDataType::class));
        self::assertTrue($this->isA->isSpecificObject($object, IsAStrictDataType::class));
        self::assertFalse($this->isA->isSpecificObject($object, 'IsAStrictDataType'));
    }
}
