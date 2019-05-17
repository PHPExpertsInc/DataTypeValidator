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

    public function setUp(): void
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

    public function testWillThrowALogicExceptionIfANonStringRuleIsGiven()
    {
        self::expectException('LogicException');
        $this->fuzzy->validate(['asdf' => true], ['asdf' => 13]);
    }
}
