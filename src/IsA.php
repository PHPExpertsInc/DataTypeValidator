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

interface IsA
{
    public const KNOWN_TYPES = ['string', 'int', 'array', 'bool', 'float', 'double', 'object', 'callable', 'resource'];

    public function isBool($value): bool;
    public function isInt($value): bool;
    public function isFloat($value): bool;
    public function isString($value): bool;
    public function isArray($value): bool;
    public function isArrayOfSomething($values, string $dataType): bool;
    public function isObject($value): bool;
    public function isCallable($value): bool;
    public function isResource($value): bool;
    public function isFuzzyObject($value, string $shortName): bool;
    public function isSpecificObject($value, string $fullName): bool;
}
