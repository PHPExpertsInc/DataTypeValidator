<?php

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
