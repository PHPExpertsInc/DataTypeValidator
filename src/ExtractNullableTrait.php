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
        // PHP 8+ optimized version
        if (str_starts_with($expectedType, '?') ||
            str_starts_with($expectedType, 'null|') ||
            str_ends_with($expectedType, '|null')) {

            if (str_starts_with($expectedType, '?')) {
                $expectedType = substr($expectedType, 1);
            } elseif (str_starts_with($expectedType, 'null|')) {
                $expectedType = substr($expectedType, 5);
            } else { // |null case
                $expectedType = substr($expectedType, 0, -5);
            }
        }

        return $expectedType;
    }
}
