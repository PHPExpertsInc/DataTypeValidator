<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator;

interface IsA
{
    public function isBool($value): bool;
    public function isInt($value): bool;
    public function isFloat($value): bool;
    public function isString($value): bool;
    public function isArray($value): bool;
    public function isObject($value): bool;
    public function isCallable($value): bool;
    public function isResource($value): bool;
    public function isFuzzyObject($value, string $shortName): bool;
    public function isSpecificObject($value, string $fullName): bool;
}
