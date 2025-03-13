<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2025 PHP Experts, Inc.
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

// Dummy classes for object validation tests.
class TestDummy {}
class AnotherDummy {}

/** @testdox Extended assertIsAType Tests */
class AssertIsATypeTest extends TestCase
{
    private $strict;
    private $fuzzy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());
    }

    private function assertPassValues($validator, array $values, string $type): void
    {
        foreach ($values as $value) {
            try {
                $validator->assertIsType($value, $type);
            } catch (InvalidDataTypeException $e) {
                $this->fail("Expected " . var_export($value, true) . " to be accepted as $type: " . $e->getMessage());
            }
        }
        $this->assertTrue(true);
    }

    private function assertFailValues($validator, array $values, string $type): void
    {
        foreach ($values as $value) {
            try {
                $validator->assertIsType($value, $type);
                $this->fail("Expected " . var_export($value, true) . " to be rejected as $type.");
            } catch (InvalidDataTypeException $e) {
                $this->assertTrue(true);
            }

            if (is_resource($value)) {
                fclose($value);
            }
        }
    }

    // === Strict Tests ===

    /** @testdox has extended tests for asserting it is a strict string */
    public function testAssertIsStrictString(): void
    {
        $values = ["hello", "", "0", "123", "true", "false", " "];
        $this->assertPassValues($this->strict, $values, "string");
    }

    /** @testdox has extended tests for asserting it is a strict int */
    public function testAssertIsStrictInt(): void
    {
        $values = [123, -456, 0];
        $this->assertPassValues($this->strict, $values, "int");
    }

    /** @testdox has extended tests for asserting it is a strict bool */
    public function testAssertIsStrictBool(): void
    {
        $values = [true, false];
        $this->assertPassValues($this->strict, $values, "bool");
    }

    /** @testdox has extended tests for asserting it is a strict array */
    public function testAssertIsStrictArray(): void
    {
        $values = [[], [1, 2, 3], ["a", "b"], [1 => 'a', 2 => 'b']];
        $this->assertPassValues($this->strict, $values, "array");
    }

    /** @testdox has extended tests for asserting it is a strict specific object */
    public function testAssertIsStrictSpecificObject(): void
    {
        $values = [new TestDummy()];
        $this->assertPassValues($this->strict, $values, TestDummy::class);
    }

    // === Fuzzy Tests ===

    /** @testdox has extended tests for asserting it is a fuzzy string */
    public function testAssertIsFuzzyString(): void
    {
        $values = ["hello", "", "0", "123", "true", "false", " "];
        $this->assertPassValues($this->fuzzy, $values, "string");
    }

    /** @testdox has extended tests for asserting it is a fuzzy int */
    public function testAssertIsFuzzyInt(): void
    {
        $values = [123, -456, 0, "123", "-456", "0"];
        $this->assertPassValues($this->fuzzy, $values, "int");
    }

    /** @testdox has extended tests for asserting it is a fuzzy bool */
    public function testAssertIsFuzzyBool(): void
    {
        $values = [true, false, null, 0, 1, "0", "1", "true", "false", 0.1, 123];
        $this->assertPassValues($this->fuzzy, $values, "bool");
    }

    /** @testdox has extended tests for asserting it is a fuzzy array */
    public function testAssertIsFuzzyArray(): void
    {
        $values = [[], [1, 2, 3], ["a", "b"], [1 => 'a', 2 => 'b']];
        $this->assertPassValues($this->fuzzy, $values, "array");
    }

    /** @testdox has extended tests for asserting it is a fuzzy object */
    public function testAssertIsFuzzyObject(): void
    {
        $values = [new TestDummy()];
        $this->assertPassValues($this->fuzzy, $values, 'TestDummy');
    }

    /** @testdox has extended tests for asserting it is a fuzzy specific object */
    public function testAssertIsFuzzySpecificObject(): void
    {
        $values = [new TestDummy()];
        $this->assertPassValues($this->fuzzy, $values, TestDummy::class);
    }

    // === NOT Strict Tests ===

    /** @testdox has extended tests for asserting it is not a strict string */
    public function testAssertIsNotStrictString(): void
    {
        $values = [123, 1.23, true, false, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->strict, $values, "string");
    }

    /** @testdox has extended tests for asserting it is not a strict int */
    public function testAssertIsNotStrictInt(): void
    {
        $values = ["123", "abc", 1.23, true, false, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->strict, $values, "int");
    }

    /** @testdox has extended tests for asserting it is not a strict bool */
    public function testAssertIsNotStrictBool(): void
    {
        $values = ["true", "false", 0, 1, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->strict, $values, "bool");
    }

    /** @testdox has extended tests for asserting it is not a strict array */
    public function testAssertIsNotStrictArray(): void
    {
        $values = ["not an array", 123, 1.23, true, false, null, new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->strict, $values, "array");
    }

    /** @testdox has extended tests for asserting it is not a strict specific object */
    public function testAssertIsNotStrictSpecificObject(): void
    {
        $values = [new AnotherDummy(), "not an object", 123, 1.23, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->strict, $values, TestDummy::class);
    }

    /** @testdox has extended tests for asserting it is not a fuzzy string */
    public function testAssertIsNotFuzzyString(): void
    {
        $values = [123, 1.23, true, false, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, "string");
    }

    /** @testdox has extended tests for asserting it is not a fuzzy int */
    public function testAssertIsNotFuzzyInt(): void
    {
        $values = ["abc", 1.23, -1.23, true, false, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, "int");
    }

    /** @testdox has extended tests for asserting it is not a fuzzy bool */
    public function testAssertIsNotFuzzyBool(): void
    {
        $values = [new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, "bool");
    }

    /** @testdox has extended tests for asserting it is not a fuzzy array */
    public function testAssertIsNotFuzzyArray(): void
    {
        $values = ["not an array", 123, 1.23, true, false, null, new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, "array");
    }

    /** @testdox has extended tests for asserting it is not a fuzzy object */
    public function testAssertIsNotFuzzyObject(): void
    {
        $values = ["not an object", 123, 1.23, null, [], [1, 2, 3], new AnotherDummy(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, 'TestDummy');
    }

    /** @testdox has extended tests for asserting it is not a fuzzy specific object */
    public function testAssertIsNotFuzzySpecificObject(): void
    {
        $values = ["not an object", 123, 1.23, null, [], [1, 2, 3], new \stdClass(), fopen('php://memory', 'r')];
        $this->assertFailValues($this->fuzzy, $values, TestDummy::class);
    }
}
