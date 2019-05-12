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

abstract class IsADataType implements IsA
{
    /**
     * @internal
     * @param mixed ...$args
     * @return bool
     */
    public function isFuzzy(...$args): bool
    {
        return $this->isFuzzyObject(...$args);
    }

    /**
     * @internal
     * @param mixed ...$args
     * @return bool
     */
    public function isSpecific(...$args): bool
    {
        return $this->isSpecificObject(...$args);
    }

    public function isType($value, $dataType, string $extra = null): bool
    {
        $isA = "is{$dataType}";
        return $this->$isA($value, $extra);
    }
}
