<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator;

class IsAFuzzyDataType extends IsAStrictDataType
{
    public function isBool($value): bool
    {
        return is_bool($value) || $value === null || !in_array(gettype($value), ['object', 'resource', 'unknown type']);
    }

    public function isInt($value): bool
    {
        return is_int($value) || filter_var($value, FILTER_VALIDATE_INT);
    }

    public function isFloat($value): bool
    {
        return is_float($value) || filter_var($value, FILTER_VALIDATE_FLOAT);
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
