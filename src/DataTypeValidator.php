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

namespace PHPExperts\DataTypeValidator;

use LogicException;

final class DataTypeValidator implements IsA
{
    public function __construct(private IsADataType $isA)
    {
    }

    public function getValidationType(): string
    {
        return $this->isA::class;
    }

    public function isBool(mixed $value): bool
    {
        return $this->isA->isBool($value);
    }

    public function isInt(mixed $value): bool
    {
        return $this->isA->isInt($value);
    }

    public function isFloat(mixed $value): bool
    {
        return $this->isA->isFloat($value);
    }

    public function isString(mixed $value): bool
    {
        return $this->isA->isString($value);
    }

    public function isArray(mixed $value): bool
    {
        return $this->isA->isArray($value);
    }

    public function isArrayOfSomething(mixed $values, string $dataType): bool
    {
        return $this->isA->isArrayOfSomething($values, $dataType);
    }

    public function isObject(mixed $value): bool
    {
        return $this->isA->isObject($value);
    }

    public function isCallable(mixed $value): bool
    {
        return $this->isA->isCallable($value);
    }

    public function isResource(mixed $value): bool
    {
        return $this->isA->isResource($value);
    }

    public function isFuzzyObject(mixed $value, string $shortName): bool
    {
        return $this->isA->isFuzzyObject($value, $shortName);
    }

    public function isSpecificObject(mixed $value, string $fullName): bool
    {
        return $this->isA->isSpecificObject($value, $fullName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsBool(mixed $value): void
    {
        $this->assertIsType($value, 'bool');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsInt(mixed $value): void
    {
        $this->assertIsType($value, 'int');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsFloat(mixed $value): void
    {
        $this->assertIsType($value, 'float');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsString(mixed $value): void
    {
        $this->assertIsType($value, 'string');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsArray(mixed $value): void
    {
        $this->assertIsType($value, 'array');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsObject(mixed $value): void
    {
        $this->assertIsType($value, 'object');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsCallable(mixed $value): void
    {
        $this->assertIsType($value, 'callable');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsResource(mixed $value): void
    {
        $this->assertIsType($value, 'resource');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsSpecificObject(mixed $value, string $className): void
    {
        $this->assertIsType($value, $className);
    }

    public function assertIsArrayOfSomething(mixed $values, string $dataType): void
    {
        $this->assertIsArray($values);

        $dataType = str_ends_with($dataType, '[]') ? substr($dataType, 0, -2) : $dataType;
        foreach ($values as $i => $value) {
            if (!$this->isA->isType($value, $dataType)) {
                throw new InvalidDataTypeException("Index '$i' is not a valid '$dataType'.");
            }
        }
    }

    /** @throws InvalidDataTypeException */
    public function assertIsType(mixed $value, string $dataType): void
    {
        // We can just let PHP deal with user error when it comes to undefined method names :-/
        $isA = "is{$dataType}";

        if (!in_array($dataType, IsA::KNOWN_TYPES)) {
            $isA = str_contains($dataType, '\\') ? 'isSpecificObject' : 'isFuzzyObject';
        }

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        if ($this->isA->$isA($value, $dataType) !== true) {
            $aAn = in_array($dataType[0], ['a', 'e', 'i', 'o', 'u']) ? 'an' : 'a';
            // Handle data types that cannot be converted to strings.
            if (!in_array(gettype($value), ['string', 'int', 'float', 'double', 'resource'])) {
                $value = substr((string) json_encode($value), 0, 15);
            }

            throw new InvalidDataTypeException("'$value' is not $aAn $dataType.");
        }
    }

    /**
     * Validates an array of values for the proper types; Laravel-esque.
     *
     * @param array<string, mixed> $values
     * @param array<string, string> $rules
     * @return bool
     * @throws InvalidDataTypeException
     */
    public function validate(array $values, array $rules): bool
    {
        $reasons = [];
        foreach ($rules as $key => $expectedType) {
            if (!$this->isString($expectedType)) {
                throw new LogicException("The data type for $key is not a string.");
            }

            // Handle arrays-of-something.
            if (str_contains($expectedType, '[]')) {
                try {
                    $this->validateArraysOfSomething($values[$key] ?? null, $expectedType);
                } catch (InvalidDataTypeException $e) {
                    $reasons[$key] = "$key is not a valid array of $expectedType: " . $e->getMessage();
                }
                continue;
            }

            try {
                $this->validateValue($values[$key] ?? null, $expectedType);
            } catch (InvalidDataTypeException) {
                $expectedType = $this->extractNullableProperty($expectedType);
                $reasons[$key] = "$key is not a valid $expectedType";
            }
        }

        if (!empty($reasons)) {
            $count = count($reasons);
            $s = $count > 1 ? 's' : '';
            $wasWere = $count > 1 ? 'were' : 'was';
            throw new InvalidDataTypeException("There $wasWere $count validation error{$s}.", $reasons);
        }

        return true;
    }

    private function validateValue(mixed $value, string $expectedType): void
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

    private function validateArraysOfSomething(mixed $values, string $expectedType): void
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
        if ($expectedType[0] === '?' || str_starts_with($expectedType, 'null|')) {
            $nullTokenPos = $expectedType[0] === '?' ? 1 : 5;

            // Then strip it out of the expected type.
            $expectedType = substr($expectedType, $nullTokenPos);
        }

        return $expectedType;
    }
}
