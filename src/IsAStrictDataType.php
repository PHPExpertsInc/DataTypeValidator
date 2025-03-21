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

class IsAStrictDataType extends IsADataType implements IsA
{
    public function isBool(mixed $value): bool
    {
        return is_bool($value);
    }

    public function isInt(mixed $value): bool
    {
        return is_int($value);
    }

    public function isFloat(mixed $value): bool
    {
        return is_float($value);
    }

    public function isString(mixed $value): bool
    {
        return is_string($value);
    }

    public function isArray(mixed $value): bool
    {
        return is_array($value);
    }
}
