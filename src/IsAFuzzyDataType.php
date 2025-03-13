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

class IsAFuzzyDataType extends IsAStrictDataType
{
    public function isBool($value): bool
    {
        $isSpecialType = in_array(gettype($value), ['object', 'resource', 'unknown type']);
        if ($isSpecialType === true) {
            return false;
        }

        $isBool = is_bool($value);
        $isNull = $value === null;
        $isArray = is_array($value);
        $isLooseValue = in_array($value, [true, false, 0, 1, "0", "1", 'true', 'false'], true);
        $isNumericAndGreaterThan0 = is_numeric($value) && $value >= 0.0;

        return $isBool || $isNull || $isArray || $isLooseValue || $isNumericAndGreaterThan0;
    }

    public function isInt($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        return is_int($value) || filter_var($value, FILTER_VALIDATE_INT) !== false || (float) (int) $value === $value;
    }

    public function isFloat($value): bool
    {
        return is_float($value) || filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    public function isString($value): bool
    {
        return is_string($value);
    }

    public function isArray($value): bool
    {
        return is_array($value) || is_object($value) && $value instanceof \ArrayAccess;
    }
}
