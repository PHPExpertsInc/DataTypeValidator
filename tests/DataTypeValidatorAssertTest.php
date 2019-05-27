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

use PHPExperts\DataTypeValidator\DataTypeValidator;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\DataTypeValidator: Assertions */
class DataTypeValidatorAssertTest extends TestCase
{
    /** @var DataTypeValidator */
    private $strict;

    /** @var DataTypeValidator */
    private $fuzzy;

    public function setUp(): void
    {
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());

        parent::setUp();
    }

    public function testWillAssertAValueIsABool()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsBool(true));
        $this->strict->assertIsBool('1.1');
    }

    public function testWillAssertAValueIsAnInt()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsInt(1));
        $this->strict->assertIsInt('1');
    }

    public function testWillAssertAValueIsAFloat()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsFloat(1.1));
        $this->strict->assertIsFloat('1.1');
    }

    public function testWillAssertAValueIsAString()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsString('1.1'));
        $this->strict->assertIsString(1.1);
    }

    public function testWillAssertAValueIsAnArray()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsArray([1.1]));
        $this->strict->assertIsArray(1.1);
    }

    public function testWillAssertAValueIsAnObject()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsObject(new \stdClass()));
        $this->strict->assertIsObject([]);
    }

    public function testWillAssertAValueIsACallable()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsCallable(function () {}));
        $this->strict->assertIsCallable('1.1');
    }

    public function testWillAssertAValueIsAResource()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsResource(fopen('php://memory', 'r')));
        $this->strict->assertIsResource('1.1');
    }

    public function testWillAssertAnObjectByItsShortName()
    {
        self::expectException(InvalidDataTypeException::class);
        self::assertNull(
            $this->strict->assertIsSpecificObject($this->strict, 'DataTypeValidator')
        );
        $this->strict->assertIsSpecificObject($this->strict, 'doesntnexist');
    }

    public function testWillAssertAnObjectByItsFullName()
    {
        self::assertNull(
            $this->strict->assertIsSpecificObject($this->strict, DataTypeValidator::class)
        );
    }
}
