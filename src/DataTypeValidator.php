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
    public function assertIsFuzzyObject($value, string $shortName)
    {
        $this->assertIsType($value, 'fuzzy', $shortName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsSpecificObject($value, string $fullName)
    {
        $this->assertIsType($value, 'specific', $fullName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsType($value, $dataType, string $extra = null)
    {
        // We can just let PHP deal with user error when it comes to undefined method names :-/
        $isA = "is{$dataType}";

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        if ($this->isA->$isA($value, $extra) !== true) {
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

    public function validateOrig(array $values, array $rules): bool
    {
        $reasons = [];
        foreach ($values as $key => $value) {
            if (empty($rules[$key])) {
                continue;
            }

            $expectedType = $rules[$key];
            if (!$this->isString($expectedType)) {
                throw new LogicException("The data type for $key is not a string.");
            }

            try {
                $this->validateValue($value, $expectedType);
            } catch (InvalidDataTypeException $e) {
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
        if (in_array($expectedType, ['string', 'int', 'bool', 'float', 'array', 'object', 'callable', 'resource'])) {
            $this->assertIsType($value, $expectedType);

            return;
        }
        // See if it is a specific class:
        elseif (strpos($expectedType, '\\') !== false) {
            $this->assertIsSpecificObject($value, $expectedType);

            return;
        }

        $this->assertIsFuzzyObject($value, $expectedType);
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
