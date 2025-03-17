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

interface IsA
{
    public const KNOWN_TYPES = ['string', 'int', 'array', 'bool', 'float', 'double', 'object', 'callable', 'resource'];

    public function isMixed(mixed $value): bool;
    public function isBool(mixed $value): bool;
    public function isInt(mixed $value): bool;
    public function isFloat(mixed $value): bool;
    public function isString(mixed $value): bool;
    public function isArray(mixed $value): bool;
    public function isArrayOfSomething(mixed $values, string $dataType): bool;
    public function isObject(mixed $value): bool;
    public function isCallable(mixed $value): bool;
    public function isResource(mixed $value): bool;
    public function isFuzzyObject(mixed $value, string $shortName): bool;
    public function isSpecificObject(mixed $value, string $fullName): bool;
}
