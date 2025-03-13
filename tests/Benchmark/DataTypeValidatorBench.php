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

namespace PHPExperts\DataTypeValidator\Tests\Benchmark;

use PHPExperts\DataTypeValidator\DataTypeValidator;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPExperts\DataTypeValidator\Tests\DataTypesLists;

class DataTypeValidatorBench
{
    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchValidator()
    {
        $validator = new DataTypeValidator(new IsAStrictDataType());

        $dataPairs = DataTypesLists::getValidStrictDataAndTypes();
        shuffle($dataPairs);

        foreach ($dataPairs as [$expectedType, $value]) {
            $validator->assertIsType($value, $expectedType);
        }
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNative()
    {
        $dataPairs = DataTypesLists::getValidStrictDataAndTypes();
        shuffle($dataPairs);

        foreach ($dataPairs as [$expectedType, $value]) {
            $function = "is_{$expectedType}";
            if ($function($value) !== true) {
                $aAn = in_array($expectedType[0], ['a', 'e', 'i', 'o', 'u']) ? 'an' : 'a';
                // Handle data types that cannot be converted to strings.
                if (!in_array($expectedType, ['string', 'int', 'float']) ||
                    !(is_object($value) && is_callable([$value, '__toString']))) {
                    $value = 'This';
                }

                throw new InvalidDataTypeException("'$value' is not $aAn $expectedType.");
            }
        }
    }}
