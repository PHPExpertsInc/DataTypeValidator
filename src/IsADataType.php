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

use ReflectionClass;

abstract class IsADataType implements IsA
{
    public function isType(mixed $value, string $dataType): bool
    {
        $isA = "is{$dataType}";

        if (!in_array($dataType, IsA::KNOWN_TYPES)) {
            $isA = str_contains($dataType, '\\') ? 'isSpecificObject' : 'isFuzzyObject';
        }

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        return $this->$isA($value, $dataType);
    }

    public function isArrayOfSomething(mixed $values, string $dataType): bool
    {
        if (!$this->isArray($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (!$this->isType($value, $dataType)) {
                return false;
            }
        }

        return true;
    }

    public function isObject(mixed $value): bool
    {
        return is_object($value);
    }

    public function isCallable(mixed $value): bool
    {
        return is_callable($value);
    }

    public function isResource(mixed $value): bool
    {
        return is_resource($value);
    }

    public function isFuzzyObject(mixed $value, string $shortName): bool
    {
        if (!is_object($value)) {
            return false;
        }

        $actualShortName = (new ReflectionClass($value))->getShortName();

        return strtolower($shortName) === strtolower($actualShortName);
    }

    public function isSpecificObject(mixed $value, string $fullName): bool
    {
        if (!is_object($value)) {
            return false;
        }

        return $fullName === $value::class;
    }
}
