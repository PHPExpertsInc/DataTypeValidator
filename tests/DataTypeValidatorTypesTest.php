<?php declare(strict_types=1);

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

    public function setUp(): void
    {
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());

        parent::setUp();
    }

    private function getDataByType(string $dataType, array $dataAndTypes): array
    {
        $out = [];
        foreach ($dataAndTypes as [$type, $value]) {
            if ($type === $dataType) {
                $out[] = $value;
            }
        }

        return $out;
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
        $this->assertTrue($this->strict->isFuzzyObject($this->strict, 'DataTypeValidator'));
        $this->assertTrue($this->fuzzy->isFuzzyObject($this->fuzzy, 'DataTypeValidator'));
        $this->assertFalse($this->strict->isFuzzyObject($this->strict, 'doesntexist'));
        $this->assertFalse($this->fuzzy->isFuzzyObject($this->fuzzy, 'doesntexist'));
    }

    public function testWillValidateObjectsByTheirFullName()
    {
        $this->assertTrue($this->strict->isSpecificObject($this->strict, DataTypeValidator::class));
        $this->assertTrue($this->fuzzy->isSpecificObject($this->fuzzy, DataTypeValidator::class));

        $this->assertFalse($this->strict->isSpecificObject($this->strict, 'DataTypeValidator'));
        $this->assertFalse($this->fuzzy->isSpecificObject($this->fuzzy, 'DataTypeValidator'));
        $this->assertFalse($this->strict->isSpecificObject($this->strict, 'doesntexist'));
        $this->assertFalse($this->fuzzy->isSpecificObject($this->fuzzy, 'doesntexist'));
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
        foreach ($values as $value) {
            self::assertFalse($this->fuzzy->isInt($value), print_r($value, true));
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
}
