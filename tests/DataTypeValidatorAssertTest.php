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

    protected function setUp(): void
    {
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());

        parent::setUp();
    }

    private function getDataByType(string $dataType, array $dataAndTypes): array
    {
        return DataTypesLists::getDataByType($dataType, $dataAndTypes);
    }

    public function testWillAssertAValueIsABool()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsBool(true));
        $this->strict->assertIsBool('1.1');
    }

    public function testWillAssertAValueIsAnInt()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsInt(1));
        $this->strict->assertIsInt('1');
    }

    public function testWillAssertAValueIsAFloat()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsFloat(1.1));
        $this->strict->assertIsFloat('1.1');
    }

    public function testWillAssertAValueIsAString()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsString('1.1'));
        $this->strict->assertIsString(1.1);
    }

    public function testWillAssertAValueIsAnArray()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsArray([1.1]));
        $this->strict->assertIsArray(1.1);
    }

    public function testWillAssertAValueIsAnObject()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsObject(new \stdClass()));
        $this->strict->assertIsObject([]);
    }

    public function testWillAssertAValueIsACallable()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsCallable(function () {}));
        $this->strict->assertIsCallable('1.1');
    }

    public function testWillAssertAValueIsAResource()
    {
        $this->expectException(InvalidDataTypeException::class);
        self::assertNull($this->strict->assertIsResource(fopen('php://memory', 'r')));
        $this->strict->assertIsResource('1.1');
    }

    public function testWillAssertAnObjectByItsShortName()
    {
        $this->expectException(InvalidDataTypeException::class);
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

    public function testWillAssertAnArrayOfSomething()
    {
        // @todo: Should we really accept *both* 'int' and 'int[]' as arrays? Maybe just 'int[]' is better?
        $goodArrays = [
            'int'      => $this->getDataByType('int', DataTypesLists::getValidStrictDataAndTypes()),
            'int[]'    => $this->getDataByType('int', DataTypesLists::getValidStrictDataAndTypes()),
            'bool'     => $this->getDataByType('bool', DataTypesLists::getValidStrictDataAndTypes()),
            'bool[]'   => $this->getDataByType('bool', DataTypesLists::getValidStrictDataAndTypes()),
            'float'    => $this->getDataByType('float', DataTypesLists::getValidStrictDataAndTypes()),
            'float[]'  => $this->getDataByType('float', DataTypesLists::getValidStrictDataAndTypes()),
            'string'   => $this->getDataByType('string', DataTypesLists::getValidStrictDataAndTypes()),
            'string[]' => $this->getDataByType('string', DataTypesLists::getValidStrictDataAndTypes()),
            'isAStrictDataType'     => [new IsAStrictDataType(), new IsAStrictDataType(), new IsAStrictDataType()],
            'isAStrictDataType[]'   => [new IsAStrictDataType(), new IsAStrictDataType(), new IsAStrictDataType()],
            'isAFuzzyDataType'      => [new IsAFuzzyDataType(), new IsAFuzzyDataType(), new IsAFuzzyDataType()],
            'isAFuzzyDataType[]'    => [new IsAFuzzyDataType(), new IsAFuzzyDataType(), new IsAFuzzyDataType()],
            isAFuzzyDataType::class => [new IsAFuzzyDataType(), new IsAFuzzyDataType(), new IsAFuzzyDataType()],
        ];

        foreach ($goodArrays as $expectedType => $array) {
            self::assertNull($this->fuzzy->assertIsArrayOfSomething($array, $expectedType));
            self::assertNull($this->strict->assertIsArrayOfSomething($array, $expectedType));
        }

        $badStrictArrays = [
            'int' => [['1'], ['1.0'], [1.0]],
            'bool' => [[0], [1], ['0'], ['1']],
            'float' => [[0], [1], ['1.1']],
            'string' => [[1], [1.0]],
        ];

        foreach ($badStrictArrays as $expectedType => $testArray) {
            foreach ($testArray as $index => $array) {
                try {
                    $this->strict->assertIsArrayOfSomething($array, $expectedType);
                    $this->fail("Index '$index' validated as a valid $expectedType when it shouldn't have.");
                } catch (InvalidDataTypeException $e) {
                    self::assertEquals("Index '0' is not a valid '$expectedType'.", $e->getMessage());
                }
            }
        }
    }
}
