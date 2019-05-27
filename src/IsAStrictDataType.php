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

use ReflectionClass;

class IsAStrictDataType extends IsADataType
{
    public function isBool($value): bool
    {
        return is_bool($value);
    }

    public function isInt($value): bool
    {
        return is_int($value);
    }

    public function isFloat($value): bool
    {
        return is_float($value);
    }

    public function isString($value): bool
    {
        return is_string($value);
    }

    public function isArray($value): bool
    {
        return is_array($value);
    }

    public function isArrayOfSomething($values, string $dataType): bool
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

    public function isObject($value): bool
    {
        return is_object($value);
    }

    public function isCallable($value): bool
    {
        return is_callable($value);
    }

    public function isResource($value): bool
    {
        return is_resource($value);
    }

    public function isFuzzyObject($value, string $shortName): bool
    {
        if (!is_object($value)) {
            return false;
        }

        $actualShortName = (new ReflectionClass($value))->getShortName();

        return strtolower($shortName) === strtolower($actualShortName);
    }

    public function isSpecificObject($value, string $fullName): bool
    {
        if (!is_object($value)) {
            return false;
        }

        return $fullName === get_class($value);
    }
}
