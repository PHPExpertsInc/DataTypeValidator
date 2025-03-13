<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2025 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/PHPExpertsInc/DataTypeValidator
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\DataTypeValidator\Tests;

abstract class DataTypesLists
{
    public static function getDataByType(string $dataType, array $dataAndTypes): array
    {
        $out = [];
        foreach ($dataAndTypes as [$type, $value]) {
            if ($type === $dataType) {
                $out[] = $value;
            }
        }

        return $out;
    }

    public static function getValidStrictDataAndTypes(): array
    {
        return [
            ['bool',             true],
            ['bool',            false],

            ['int',                 0],
            ['int',                 1],
            ['int',                 5],
            ['int',                 3],
            ['int',               544],
            ['int',               -53],

            ['float',        (float)0],
            ['float',             0.0],
            ['float',             1.2],
            ['float',             1.0],
            ['float',          51.055],
            ['float',           -1.05],
            ['float',           0.001],

            ['string',         'asdf'],
            ['string',            '1'],
            ['string',            '0'],
            ['string',          '1.5'],
            ['string',         'null'],

            ['array',              []],
            ['array',             [1]],
            ['array',    ['a' => 'a']],

            ['object',   new \stdClass()],
            ['object',   new \ReflectionClass(new \stdClass())],
            ['object',   new class {}],
            ['object',   function () {}],

            ['callable', function () {}],
            ['callable', [self::class, 'getValidStrictDataAndTypes']],
            ['callable', 'strpos'],

            ['resource', fopen('php://memory', 'r')],
        ];
    }

    public static function getValidFuzzyDataAndTypes(): array
    {
        return array_merge([
            ['bool',              '0'],
            ['bool',              '1'],
            ['bool',                0],
            ['bool',                1],
            ['bool',               []],
            ['bool',            [123]],
            ['bool',             null],

            ['int',               '1'],
            ['int',              '-1'],
            ['int',               1.0],
            ['int',              -1.0],

            ['float',             '1'],
            ['float',           '1.1'],
            ['float',            '-1'],
            ['float',          '-1.1'],

            ['array',    new class extends \ArrayObject {}],
        ], self::getValidStrictDataAndTypes());
    }

    public static function getInvalidFuzzyDataAndTypes(): array
    {
        return [
            ['bool',    new \stdClass()],
            ['bool',     function () {}],

            ['int',                null],
            ['int',               '1.1'],
            ['int',              '-1.1'],
            ['int',              'asdf'],
            ['int',                'a1'],
            ['int',                 [1]],
            ['int',     new \stdClass()],
            ['int',      function () {}],

            ['float',              null],
            ['float',            'asdf'],
            ['float',              'a1'],
            ['float',             [1.1]],
            ['float',   new \stdClass()],
            ['float',    function () {}],

            ['string',             null],
            ['string',             ['']],
            ['string',  new \stdClass()],
            ['string',   function () {}],

            ['array',              null],
            ['array',                 1],
            ['array',               1.1],

            ['object',             null],
            ['object',    'nonexisting'],
            ['object',              'a'],
            ['object',                1],
            ['object',              1.1],
            ['object',     ['a' => 'a']],
            ['object',               []],

            ['callable',           null],
            ['callable',  'dosentexist'],
            ['callable',              1],
            ['callable',            1.1],
            ['callable',             []],

            ['resource',           null],
            ['resource',  'dosentexist'],
            ['resource',              1],
            ['resource',            1.1],
            ['resource',             []],
        ];
    }

    public static function getInvalidStrictDataAndTypes(): array
    {
        return array_merge([
            ['bool',           'true'],
            ['bool',          'false'],
            ['bool',              '0'],
            ['bool',              '1'],
            ['bool',                0],
            ['bool',                1],
            ['bool',               []],
            ['bool',             null],

            ['int',                 '1'],
            ['int',               '1.0'],
            ['int',                 1.0],

            ['float',               '1'],
            ['float',             '1.1'],

            ['string',   new class extends \ArrayObject {}],
        ], self::getInvalidFuzzyDataAndTypes());
    }
}
