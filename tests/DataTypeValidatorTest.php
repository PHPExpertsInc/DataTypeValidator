<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2025 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/DataTypeValidator
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\DataTypeValidator\Tests;

use Carbon\Carbon;
use PHPExperts\DataTypeValidator\DataTypeValidator;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\DataTypeValidator */
class DataTypeValidatorTest extends TestCase
{
    /** @var DataTypeValidator */
    private $strict;

    /** @var DataTypeValidator */
    private $fuzzy;

    protected function setUp(): void
    {
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());

        parent::setUp();
    }

    public function testCanBulkValidateADataArray()
    {
        $data = [
            'name'     => 'Cheyenne',
            'age'      => 22,
            'birthday' => Carbon::parse('1996-12-04 15:15:15'),
            'daysOld'  => 8194.35,
            'today'    => Carbon::now(),
            'sayHi'    => function () { return 'Hi!'; },
            'lucky'    => [7, 2, 1],
            'single'   => false,
        ];

        $rules = [
            'name'     => 'string',
            'age'      => 'int',
            'birthday' => 'Carbon',
            'daysOld'  => 'float',
            'today'    => 'Carbon\Carbon',
            'sayHi'    => 'callable',
            'lucky'    => 'array',
            'single'   => 'bool',
        ];

        self::assertTrue($this->strict->validate($data, $rules));
    }

    public function testWillReturnTheNameOfTheDataValidatorLogic()
    {
        self::assertSame(IsAStrictDataType::class, $this->strict->getValidationType());
        self::assertSame(IsAFuzzyDataType::class, $this->fuzzy->getValidationType());
    }

    public function testWillReturnAnArrayOfInvalidKeysWithExplanations()
    {
        $data = [
            'name'     => 'Cheyenne',
            'age'      => '22',
            'birthday' => '1996-12-04 15:15:15',
            'daysOld'  => '8194.35',
        ];

        $rules = [
            'name'     => 'string',
            'age'      => 'int',
            'birthday' => 'Carbon',
            'daysOld'  => 'float',
        ];

        try {
            self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
            $this->fail('Invalid data did not throw an exception.');
        } catch (InvalidDataTypeException $e) {
            self::assertSame('There were 3 validation errors.', $e->getMessage());
            $reasons = $e->getReasons();
            self::assertNotEmpty($reasons);

            self::assertSame('age is not a valid int', $reasons['age']);
            self::assertSame('birthday is not a valid Carbon', $reasons['birthday']);
            self::assertSame('daysOld is not a valid float', $reasons['daysOld']);
            self::assertSame(['age', 'birthday', 'daysOld'], array_keys($reasons));
        }
    }

    public function testWillSilentlyIgnoreDataNotInTheRules()
    {
        $data = [
            'name'     => 'Cheyenne',
            'favFood'  => 'Italian',
        ];

        $rules = [
            'name'     => 'string',
        ];

        self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
    }

    public function testWillSilentlyIgnoreNullableRulesWithNoData()
    {
        $data = [
            'name'     => 'Cheyenne',
            'favFood'  => 'Italian',
        ];

        $rules = [
            'name'     => 'string',
            'age'      => '?int',
            'birthday' => '?Carbon',
            'daysOld'  => 'null|float',
            'favFood'  => 'string',
        ];

        self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
    }

    public function testDataCannotBeNullByDefault()
    {
        $data = [
            'name' => null,
        ];

        $rules = [
            'name' => 'string',
        ];

        self::expectException(InvalidDataTypeException::class);
        $this->strict->validate($data, $rules);
    }

    /** @testdox Any data type that starts with a '?' is nullable */
    public function testAnyDataTypeThatStartsWithAQuestionMarkIsNullable()
    {
        $data = [
            'name'     => 'Cheyenne',
            'age'      => 22,
            'birthday' => Carbon::parse('1996-12-04 15:15:15'),
            'daysOld'  => 8194.35,
            'today'    => Carbon::now(),
            'sayHi'    => function () { return 'Hi!'; },
            'lucky'    => [7, 2, 1],
            'single'   => false,
        ];

        $nullData = [
            'name'     => null,
            'age'      => null,
            'birthday' => null,
            'daysOld'  => null,
            'today'    => null,
            'sayHi'    => null,
            'lucky'    => null,
            'single'   => null,
        ];

        $rules = [
            'name'     => '?string',
            'age'      => '?int',
            'birthday' => '?Carbon',
            'daysOld'  => '?float',
            'today'    => '?Carbon\Carbon',
            'sayHi'    => '?callable',
            'lucky'    => '?array',
            'single'   => '?bool',
        ];

        try {
            self::assertTrue($this->strict->validate($data, $rules));
            self::assertTrue($this->strict->validate($nullData, $rules));
        } catch (InvalidDataTypeException $e) {
            dd($e->getReasons());
        }
    }

    /** @testdox Any data type that ends with '[]' is an array of X*/
    public function testAnyDataTypeThatEndsWithABracketsIsAnArrayOfX()
    {
        $goodValues = [
            'dates'   => [Carbon::now(), Carbon::now()->subDay()],
            'ints'    => [1, 2, 3],
            'floats'  => [1.0, 2.2],
            'strings' => ['hi', 'bye'],
        ];

        $badValues = [
            'dates'   => ['2019-05-27', Carbon::now()->subDay()],
            'ints'    => [1, 2.2, 3],
            'floats'  => [1.0, 2.2, 'asdf'],
            'strings' => ['hi', 'bye', 'asdf', 4],
        ];

        $rules = [
            'dates'   => '?Carbon[]',
            'ints'    => '?int[]',
            'floats'  => '?float[]',
            'strings' => '?string[]',
        ];

        $expected = [
            "dates"   => "dates is not a valid array of ?Carbon[]: Index '0' is not a valid 'Carbon'.",
            "ints"    => "ints is not a valid array of ?int[]: Index '1' is not a valid 'int'.",
            "floats"  => "floats is not a valid array of ?float[]: Index '2' is not a valid 'float'.",
            "strings" => "strings is not a valid array of ?string[]: Index '3' is not a valid 'string'.",
        ];

        self::assertTrue($this->fuzzy->validate($goodValues, $rules));
        self::assertTrue($this->strict->validate($goodValues, $rules));

        try {
            $this->fuzzy->validate($badValues, $rules);
        } catch (InvalidDataTypeException $e) {
            self::assertSame('There were 4 validation errors.', $e->getMessage());
            self::assertEquals($expected, $e->getReasons());
        }

        try {
            $this->strict->validate($badValues, $rules);
        } catch (InvalidDataTypeException $e) {
            self::assertSame('There were 4 validation errors.', $e->getMessage());
            self::assertEquals($expected, $e->getReasons());
        }

        $nullArray = [
            'ints' => null,
        ];

        $rules = [
            'ints' => 'int[]'
        ];

        try {
            $this->fuzzy->validate($nullArray, $rules);
        } catch (InvalidDataTypeException) {
        }
    }

    public function testWillAllowAnEmptyArrayOfSomething()
    {
        $nullableTypes = [
            '?int[]',
            '?bool[]',
            '?float[]',
            '?string[]',
            '?isAStrictDataType[]',
            '?isAFuzzyDataType[]',
            'null|int[]',
            'null|bool[]',
            'null|float[]',
            'null|string[]',
            'null|isAStrictDataType[]',
            'null|isAFuzzyDataType[]',
            '?' . isAFuzzyDataType::class . '[]',
            'null|' . isAFuzzyDataType::class . '[]',
        ];

        $rules = array_combine($nullableTypes, $nullableTypes);
        $values = array_combine($nullableTypes, array_fill(0, count($nullableTypes), []));

        foreach ($values as $expectedType => $array) {
            self::assertTrue($this->fuzzy->validate($values, $rules));
            self::assertTrue($this->strict->validate($values, $rules));
        }
    }

    public function testWillAllowANullableArrayOfSomething()
    {
        $nullableTypes = [
            '?int[]',
            '?bool[]',
            '?float[]',
            '?string[]',
            '?isAStrictDataType[]',
            '?isAFuzzyDataType[]',
            'null|int[]',
            'null|bool[]',
            'null|float[]',
            'null|string[]',
            'null|isAStrictDataType[]',
            'null|isAFuzzyDataType[]',
            '?' . isAFuzzyDataType::class . '[]',
            'null|' . isAFuzzyDataType::class . '[]',
        ];

        $rules = array_combine($nullableTypes, $nullableTypes);
        $values = array_combine($nullableTypes, array_fill(0, count($nullableTypes), null));

        foreach ($values as $expectedType => $array) {
            self::assertTrue($this->fuzzy->validate($values, $rules));
            self::assertTrue($this->strict->validate($values, $rules));
        }
    }

    public function testWillThrowALogicExceptionIfANonStringRuleIsGiven()
    {
        self::expectException('LogicException');
        $this->fuzzy->validate(['asdf' => true], ['asdf' => 13]);
    }
}
