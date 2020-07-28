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
use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\DataTypeValidator: Data Type Checks */
class DataTypeValidatorTypesTest extends TestCase
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

    public function testWillValidateBoolsStrictly()
    {
        $values = $this->getDataByType('bool', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isBool($value), print_r($value, true));
        }

        $values = $this->getDataByType('bool', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isBool($value), print_r($value, true));
        }
    }

    public function testWillValidateIntsStrictly()
    {
        $values = $this->getDataByType('int', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isInt($value), print_r($value, true));
        }

        $values = $this->getDataByType('int', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isInt($value), print_r($value, true));
        }
    }

    public function testWillValidateFloatsStrictly()
    {
        $values = $this->getDataByType('float', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isFloat($value), print_r($value, true));
        }

        $values = $this->getDataByType('float', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isFloat($value), print_r($value, true));
        }
    }

    public function testWillValidateStringsStrictly()
    {
        $values = $this->getDataByType('string', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isString($value), print_r($value, true));
        }

        $values = $this->getDataByType('string', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isString($value), print_r($value, true));
        }
    }

    public function testWillValidateArraysStrictly()
    {
        $values = $this->getDataByType('array', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isArray($value), print_r($value, true));
        }

        $values = $this->getDataByType('array', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isArray($value), print_r($value, true));
        }
    }

    public function testWillValidateObjects()
    {
        $values = $this->getDataByType('object', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isObject($value), print_r($value, true));
        }

        $values = $this->getDataByType('object', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isObject($value), print_r($value, true));
        }

        $values = $this->getDataByType('object', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isObject($value), print_r($value, true));
        }

        $values = $this->getDataByType('object', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isObject($value), print_r($value, true));
        }
    }

    public function testWillValidateCallables()
    {
        $values = $this->getDataByType('callable', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isCallable($value), print_r($value, true));
        }

        $values = $this->getDataByType('callable', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isCallable($value), print_r($value, true));
        }

        $values = $this->getDataByType('callable', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isCallable($value), print_r($value, true));
        }

        $values = $this->getDataByType('callable', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isCallable($value), print_r($value, true));
        }
    }

    public function testWillValidateResources()
    {
        $values = $this->getDataByType('resource', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->strict->isResource($value), print_r($value, true));
        }

        $values = $this->getDataByType('resource', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->strict->isResource($value), print_r($value, true));
        }

        $values = $this->getDataByType('resource', DataTypesLists::getValidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isResource($value), print_r($value, true));
        }

        $values = $this->getDataByType('resource', DataTypesLists::getInvalidStrictDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isResource($value), print_r($value, true));
        }
    }

    public function testWillValidateObjectsByTheirShortName()
    {
        self::assertTrue($this->strict->isFuzzyObject($this->strict, 'DataTypeValidator'));
        self::assertTrue($this->fuzzy->isFuzzyObject($this->fuzzy, 'DataTypeValidator'));
        self::assertFalse($this->strict->isFuzzyObject($this->strict, 'doesntexist'));
        self::assertFalse($this->fuzzy->isFuzzyObject($this->fuzzy, 'doesntexist'));
    }

    public function testWillValidateObjectsByTheirFullName()
    {
        self::assertTrue($this->strict->isSpecificObject($this->strict, DataTypeValidator::class));
        self::assertTrue($this->fuzzy->isSpecificObject($this->fuzzy, DataTypeValidator::class));

        self::assertFalse($this->strict->isSpecificObject($this->strict, 'DataTypeValidator'));
        self::assertFalse($this->fuzzy->isSpecificObject($this->fuzzy, 'DataTypeValidator'));
        self::assertFalse($this->strict->isSpecificObject($this->strict, 'doesntexist'));
        self::assertFalse($this->fuzzy->isSpecificObject($this->fuzzy, 'doesntexist'));
    }

    public function testCanValidateBoolsLoosely()
    {
        $values = $this->getDataByType('bool', DataTypesLists::getValidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isBool($value), print_r($value, true));
        }

        $values = $this->getDataByType('bool', DataTypesLists::getInvalidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isBool($value), print_r($value, true));
        }
    }

    public function testCanValidateIntsLoosely()
    {
        $values = $this->getDataByType('int', DataTypesLists::getValidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isInt($value), print_r($value, true));
        }

        $values = $this->getDataByType('int', DataTypesLists::getInvalidFuzzyDataAndTypes());
        foreach ($values as $index => $value) {
            self::assertFalse($this->fuzzy->isInt($value), print_r($value, true) . ' is a valid int');
        }
    }

    public function testCanValidateFloatsLoosely()
    {
        $values = $this->getDataByType('float', DataTypesLists::getValidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isFloat($value), print_r($value, true));
        }

        $values = $this->getDataByType('float', DataTypesLists::getInvalidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isFloat($value), print_r($value, true));
        }
    }

    public function testCanValidateStringsLoosely()
    {
        $values = $this->getDataByType('string', DataTypesLists::getValidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isString($value), print_r($value, true));
        }

        $values = $this->getDataByType('string', DataTypesLists::getInvalidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isString($value), print_r($value, true));
        }
    }

    public function testCanValidateArraysLoosely()
    {
        $values = $this->getDataByType('array', DataTypesLists::getValidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertTrue($this->fuzzy->isArray($value), print_r($value, true));
        }

        $values = $this->getDataByType('array', DataTypesLists::getInvalidFuzzyDataAndTypes());
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isArray($value), print_r($value, true));
        }
    }

    public function testWillValidateArraysOfSomething()
    {
        $arrays = [
            'int'    => $this->getDataByType('int', DataTypesLists::getValidStrictDataAndTypes()),
            'bool'   => $this->getDataByType('bool', DataTypesLists::getValidStrictDataAndTypes()),
            'float'  => $this->getDataByType('float', DataTypesLists::getValidStrictDataAndTypes()),
            'string' => $this->getDataByType('string', DataTypesLists::getValidStrictDataAndTypes()),
            'isAStrictDataType'     => [new IsAStrictDataType(), new IsAStrictDataType(), new IsAStrictDataType()],
            'isAFuzzyDataType'      => [new IsAFuzzyDataType(), new IsAFuzzyDataType(), new IsAFuzzyDataType()],
            isAFuzzyDataType::class => [new IsAFuzzyDataType(), new IsAFuzzyDataType(), new IsAFuzzyDataType()],
        ];

        foreach ($arrays as $expectedType => $array) {
            self::assertTrue($this->fuzzy->isArrayOfSomething($array, $expectedType), "An array of {$expectedType}s didn't validate.");
            self::assertTrue($this->strict->isArrayOfSomething($array, $expectedType), "An array of {$expectedType}s didn't validate.");
        }
    }
}
