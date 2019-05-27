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
    public function isType($value, $dataType): bool
    {
        $isA = "is{$dataType}";

        if (!in_array($dataType, IsA::KNOWN_TYPES)) {
            $isA = strpos($dataType, '\\') !== false ? 'isSpecificObject' : 'isFuzzyObject';
        }

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        return $this->$isA($value, $dataType);
    }
}
