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

namespace PHPExperts\DataTypeValidator;

use LogicException;

final class DataTypeValidator implements IsA
{
    /** @var IsADataType */
    private $isA;

    public function __construct(IsADataType $isA)
    {
        $this->isA = $isA;
    }

    public function getValidationType(): string
    {
        return get_class($this->isA);
    }

    public function isBool($value): bool
    {
        return $this->isA->isBool($value);
    }

    public function isInt($value): bool
    {
        return $this->isA->isInt($value);
    }

    public function isFloat($value): bool
    {
        return $this->isA->isFloat($value);
    }

    public function isString($value): bool
    {
        return $this->isA->isString($value);
    }

    public function isArray($value): bool
    {
        return $this->isA->isArray($value);
    }

    public function isArrayOfSomething($values, string $dataType): bool
    {
        return $this->isA->isArrayOfSomething($values, $dataType);
    }

    public function isObject($value): bool
    {
        return $this->isA->isObject($value);
    }

    public function isCallable($value): bool
    {
        return $this->isA->isCallable($value);
    }

    public function isResource($value): bool
    {
        return $this->isA->isResource($value);
    }

    public function isFuzzyObject($value, string $shortName): bool
    {
        return $this->isA->isFuzzyObject($value, $shortName);
    }

    public function isSpecificObject($value, string $fullName): bool
    {
        return $this->isA->isSpecificObject($value, $fullName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsBool($value)
    {
        $this->assertIsType($value, 'bool');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsInt($value)
    {
        $this->assertIsType($value, 'int');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsFloat($value)
    {
        $this->assertIsType($value, 'float');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsString($value)
    {
        $this->assertIsType($value, 'string');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsArray($value)
    {
        $this->assertIsType($value, 'array');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsObject($value)
    {
        $this->assertIsType($value, 'object');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsCallable($value)
    {
        $this->assertIsType($value, 'callable');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsResource($value)
    {
        $this->assertIsType($value, 'resource');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsSpecificObject($value, string $className)
    {
        $this->assertIsType($value, $className);
    }

    public function assertIsArrayOfSomething($values, string $dataType)
    {
        $this->assertIsArray($values);

        $dataType = substr($dataType, -2) === '[]' ? substr($dataType, 0, -2) : $dataType;
        foreach ($values as $i => $value) {
            if (!$this->isA->isType($value, $dataType)) {
                throw new InvalidDataTypeException("Index '$i' is not a valid '$dataType'.");
            }
        }
    }

    /** @throws InvalidDataTypeException */
    public function assertIsType($value, $dataType): void
    {
        // We can just let PHP deal with user error when it comes to undefined method names :-/
        $isA = "is{$dataType}";

        if (!in_array($dataType, IsA::KNOWN_TYPES)) {
            $isA = strpos($dataType, '\\') !== false ? 'isSpecificObject' : 'isFuzzyObject';
        }

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        if ($this->isA->$isA($value, $dataType) !== true) {
            $aAn = in_array($dataType[0], ['a', 'e', 'i', 'o', 'u']) ? 'an' : 'a';
            // Handle data types that cannot be converted to strings.
            if (!in_array(gettype($value), ['string', 'int', 'float', 'double'])) {
                $value = substr((string) json_encode($value), 0, 15);
            }

            throw new InvalidDataTypeException("'$value' is not $aAn $dataType.");
        }
    }

    /**
     * Validates an array of values for the proper types; Laravel-esque.
     *
     * @param array $values
     * @param array $rules
     * @return bool true if completely valid.
     * @throws InvalidDataTypeException if one or more values are not the correct data type.
     */
    public function validate(array $values, array $rules): bool
    {
        $reasons = [];
        foreach ($rules as $key => $expectedType) {
            if (!$this->isString($expectedType)) {
                throw new LogicException("The data type for $key is not a string.");
            }

            // Handle arrays-of-something.
            if (strpos($expectedType, '[]') !== false) {
                try {
                    $this->validateArraysOfSomething($values[$key] ?? null, $expectedType);
                } catch (InvalidDataTypeException $e) {
                    $reasons[$key] = "$key is not a valid array of $expectedType: " . $e->getMessage();
                }

                continue;
            }

            try {
                $this->validateValue($values[$key] ?? null, $expectedType);
            } catch (InvalidDataTypeException $e) {
                $expectedType = $this->extractNullableProperty($expectedType);
                $reasons[$key] = "$key is not a valid $expectedType";
            }
        }

        if (!empty($reasons)) {
            $count = count($reasons);
            $s = $count > 1 ? 's': '';
            $wasWere = $count > 1 ? 'were' : 'was';
            throw new InvalidDataTypeException("There $wasWere $count validation error{$s}.", $reasons);
        }

        return true;
    }

    private function validateValue($value, string $expectedType)
    {
        // Allow nullable types.
        $nullableType = $this->extractNullableProperty($expectedType);
        if ($nullableType !== $expectedType) {
            if ($value === null) {
                return;
            }

            $expectedType = $nullableType;
        }

        // Traditional values.
        if (in_array($expectedType, IsA::KNOWN_TYPES)) {
            $this->assertIsType($value, $expectedType);

            return;
        }

        // See if it is a specific class:
        $this->assertIsSpecificObject($value, $expectedType);
    }

    private function validateArraysOfSomething($values, string $expectedType)
    {
        // Allow nullable types.
        $nullableType = $this->extractNullableProperty($expectedType);
        if ($nullableType !== $expectedType) {
            // If the data type is nullable and the value is null, let's bail early.
            if ($values === null) {
                return;
            }

            $expectedType = $nullableType;
        }

        $this->assertIsArrayOfSomething($values, substr($expectedType, 0, -2));
    }

    private function extractNullableProperty(string $expectedType): string
    {
        if ($expectedType[0] === '?' || substr($expectedType, 0, 5) === 'null|') {
            $nullTokenPos = $expectedType[0] === '?' ? 1 : 5;

            // Then strip it out of the expected type.
            $expectedType = substr($expectedType, $nullTokenPos ?? 1);
        }

        return $expectedType;
    }
}
