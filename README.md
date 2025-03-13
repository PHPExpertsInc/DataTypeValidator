# DataTypeValidator

[![TravisCI](https://travis-ci.org/phpexpertsinc/DataTypeValidator.svg?branch=master)](https://travis-ci.org/phpexpertsinc/DataTypeValidator)
[![Maintainability](https://api.codeclimate.com/v1/badges/5d56aa8b847dce751598/maintainability)](https://codeclimate.com/github/phpexpertsinc/DataTypeValidator/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5d56aa8b847dce751598/test_coverage)](https://codeclimate.com/github/phpexpertsinc/DataTypeValidator/test_coverage)

DataTypeValidator is a PHP Experts, Inc., Project designed for easy data type validation.

It supports both traditional, fuzzy, PHP data types (e.g., "1.0" can be both a float, int, and string)
and strict data type validations ('1' is only a string, 1.0 is only a float, etc.).

## Installation

Via Composer

```bash
composer require phpexperts/datatype-validator
```

## Usage

```php
// 1. Pick a Type Checker (IsAFuzzyDataType or IsAStrictDataType).
//    * IsAFuzzyDataType tries its best to emulate PHP's `==`.
//    * IsAStrictDataType observes PHP's `strict_types=1` rules.
    $validator = new DataValidator(new IsAStrictDataType());

// There are two powerful mechanisms out of the box:
// 2. It is easy to validate any data type dynamically, without a ton of if statements.

    $validator->isType('asdf', 'string'); // true or false.
    $validator->assertIsType(1, 'int'); // null or throws InvalidDataTypeException

// 3. You can also validate arrays:

    $data = [
        'name'     => 'Cheyenne',
        'age'      => 22,
        'birthday' => Carbon::parse('1996-12-04 15:15:15'),
        'daysOld'  => 8194.35,
        'today'    => Carbon::now(),
        'sayHi'    => function () { return 'Hi!'; },
    ];
    
    $rules = [
         'name'     => 'string',
         'age'      => 'int',
         'birthday' => 'Carbon',
         'daysOld'  => 'float',
         'today'    => 'Carbon\Carbon',
         'sayHi'    => 'callable',
     ];

    $validator->validate($data, $rules);

// 4. DataValidator::validate() will return `true` on success or throw 
//    an `InvalidDataTypeException` that contains an array of errors:

    $data = [
        'name'     => 'Cheyenne',
        'age'      => '22',
        'birthday' => '1996-12-04 15:15:15',
    ];
    
    try {
        $validator->validate($data, $rules);
    } catch (InvalidDataTypeException $e) {
        print_r($e->getReasons());

        /* Output:
        array:2 [
          0 => "age is not a valid int"
          1 => "birthday is not a valid Carbon"
        ]
        */
    }

// 5. It can validate objects based on their short name or full name.
    $data = [
        'yesterday' => \Carbon\Carbon::parse('2019-05-11'),
        'tomorrow'  => \Carbon\Carbon::parse('2019-05-13'),
    ];
    
    $validator->validate($data, [
        'yesterday' => '\Carbon\Carbon',
        'tomorrow'  => 'Carbon',
    ]);

```

## Benchmarks
```bash
phpbench run --report=aggregate
```

PHP v8.4 with opcache enabled
+------------------------+----------------+------+-----------+---------+---------+---------+---------+---------+--------+---------+
| benchmark              | subject        | revs | mem_peak  | best    | mean    | mode    | worst   | stdev   | rstdev | diff    |
+------------------------+----------------+------+-----------+---------+---------+---------+---------+---------+--------+---------+
| DataTypeValidatorBench | benchValidator | 1000 | 832.896kb | 4.414μs | 4.803μs | 4.876μs | 5.112μs | 0.233μs | ±4.85% | +2.57%  |
| DataTypeValidatorBench | benchNative    | 1000 | 832.896kb | 2.670μs | 2.866μs | 2.745μs | 3.356μs | 0.250μs | ±8.71% | -42.25% |
+------------------------+----------------+------+-----------+---------+---------+---------+---------+---------+--------+---------+


# Use cases

PHPExperts\DataTypeValidator\DataTypeValidator  
 ✔ Can bulk validate a data array  
 ✔ Will return the name of the data validator logic  
 ✔ Will return an array of invalid keys with explanations  
 ✔ Will silently ignore data not in the rules  
 ✔ Will silently ignore nullable rules with no data  
 ✔ Data cannot be null by default  
 ✔ Any data type that starts with a '?' is nullable  
 ✔ Any data type that ends with '[]' is an array of X  
 ✔ Will allow an empty array of something  
 ✔ Will allow a nullable array of something  
 ✔ Will throw a logic exception if a non string rule is given  

PHPExperts\DataTypeValidator\DataTypeValidator: Assertions  
 ✔ Will assert a value is a bool  
 ✔ Will assert a value is an int  
 ✔ Will assert a value is a float  
 ✔ Will assert a value is a string  
 ✔ Will assert a value is an array  
 ✔ Will assert a value is an object  
 ✔ Will assert a value is a callable  
 ✔ Will assert a value is a resource  
 ✔ Will assert an array of something  
 ✔ Will assert an object by its short name  
 ✔ Will assert an object by its full name  

PHPExperts\DataTypeValidator\DataTypeValidator: Data Type Checks  
 ✔ Will validate bools strictly  
 ✔ Will validate ints strictly  
 ✔ Will validate floats strictly  
 ✔ Will validate strings strictly  
 ✔ Will validate arrays strictly  
 ✔ Will validate objects  
 ✔ Will validate callables  
 ✔ Will validate resources  
 ✔ Will validate objects by their short name  
 ✔ Will validate objects by their full name  
 ✔ Can validate bools loosely  
 ✔ Can validate ints loosely  
 ✔ Can validate floats loosely  
 ✔ Can validate strings loosely  
 ✔ Can validate arrays loosely  
 ✔ Will validate arrays of something  

PHPExperts\DataTypeValidator\IsAFuzzyDataType  
 ✔ Will return true for valid values  
 ✔ Will return false for invalid values  
 ✔ Will match short classes  
 ✔ Will match specific classes  
 ✔ Will work with an array of something  

PHPExperts\DataTypeValidator\IsAStrictDataType  
 ✔ Will return true for valid values  
 ✔ Will return false for invalid values  
 ✔ Will match short classes  
 ✔ Will match specific classes  
 ✔ Will work with an array of something  

Extended assertIsAType Tests  
✔ has extended tests for asserting it is a strict string  
✔ has extended tests for asserting it is a strict int  
✔ has extended tests for asserting it is a strict bool  
✔ has extended tests for asserting it is a strict array  
✔ has extended tests for asserting it is a strict specific object  
✔ has extended tests for asserting it is a fuzzy string  
✔ has extended tests for asserting it is a fuzzy int  
✔ has extended tests for asserting it is a fuzzy bool  
✔ has extended tests for asserting it is a fuzzy array  
✔ has extended tests for asserting it is a fuzzy object  
✔ has extended tests for asserting it is a fuzzy specific object  
✔ has extended tests for asserting it is not a strict string  
✔ has extended tests for asserting it is not a strict int  
✔ has extended tests for asserting it is not a strict bool  
✔ has extended tests for asserting it is not a strict array  
✔ has extended tests for asserting it is not a strict specific object  
✔ has extended tests for asserting it is not a fuzzy string  
✔ has extended tests for asserting it is not a fuzzy int  
✔ has extended tests for asserting it is not a fuzzy bool  
✔ has extended tests for asserting it is not a fuzzy array  
✔ has extended tests for asserting it is not a fuzzy object  
✔ has extended tests for asserting it is not a fuzzy specific object  

## Testing

```bash
phpunit
```

# Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.

