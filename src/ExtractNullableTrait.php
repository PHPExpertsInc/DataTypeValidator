<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2025 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/DataTypeValidator
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\DataTypeValidator;

trait ExtractNullableTrait
{
    private function extractNullableProperty(string $expectedType): string
    {
        if ($expectedType[0] === '?' || substr($expectedType, 0, 5) === 'null|') {
            $nullTokenPos = $expectedType[0] === '?' ? 1 : 5;

            // Then strip it out of the expected type.
            $expectedType = substr($expectedType, $nullTokenPos);
        }

        return $expectedType;
    }
}
