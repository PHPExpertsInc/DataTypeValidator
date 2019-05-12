# DataTypeValidator

[![TravisCI](https://travis-ci.org/phpexpertsinc/DataTypeValidator.svg?branch=master)](https://travis-ci.org/phpexpertsinc/DataTypeValidator)

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

| benchmark              | subject        | set | revs | its | mem_peak   | best     | mean     | mode     | worst    | stdev   | rstdev | diff  |
|------------------------|----------------|-----|------|-----|------------|----------|----------|----------|----------|---------|--------|-------|
| DataTypeValidatorBench | benchValidator | 0   | 1000 | 5   | 1,403,528b | 51.278μs | 52.566μs | 53.167μs | 54.005μs | 1.087μs | 2.07%  | 2.57x |
| DataTypeValidatorBench | benchNative    | 0   | 1000 | 5   | 1,357,368b | 19.313μs | 20.455μs | 20.866μs | 21.221μs | 0.715μs | 3.50%  | 1.00x |

# Use cases

PHPExperts\DataTypeValidator\DataTypeValidator  
 ✔ Can bulk validate a data array  
 ✔ Will return an array of invalid keys with explanations  
 ✔ Will silently ignore data not in the rules  
 ✔ Will silently ignore rules with no data  

PHPExperts\DataTypeValidator\DataTypeValidator: Assertions  
 ✔ Will assert a value is a bool  
 ✔ Will assert a value is an int  
 ✔ Will assert a value is a float  
 ✔ Will assert a value is a string  
 ✔ Will assert a value is an array  
 ✔ Will assert a value is an object  
 ✔ Will assert a value is a callable  
 ✔ Will assert a value is a resource  
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

PHPExperts\DataTypeValidator\IsAFuzzyDataType  
 ✔ Will return true for valid values  
 ✔ Will return false for invalid values  
 ✔ Will match short classes  
 ✔ Will match specific classes  

PHPExperts\DataTypeValidator\IsAStrictDataType  
 ✔ Will return true for valid values  
 ✔ Will return false for invalid values  
 ✔ Will match short classes  
 ✔ Will match specific classes

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

