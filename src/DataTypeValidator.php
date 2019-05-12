<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator;

class DataTypeValidator implements IsA
{
    /** @var IsADataType */
    protected $isA;

    public function __construct(IsADataType $isA)
    {
        $this->isA = $isA;
    }

    public function isBool($value): bool
    {
        return $this->isA->isBool($value);
    }

    public function isInt($value): bool
    {
        return $this->isA->isInt($value);
    }

    public function isFloat($value): bool
    {
        return $this->isA->isFloat($value);
    }

    public function isString($value): bool
    {
        return $this->isA->isString($value);
    }

    public function isArray($value): bool
    {
        return $this->isA->isArray($value);
    }

    public function isObject($value): bool
    {
        return $this->isA->isObject($value);
    }

    public function isCallable($value): bool
    {
        return $this->isA->isCallable($value);
    }

    public function isResource($value): bool
    {
        return $this->isA->isResource($value);
    }

    public function isFuzzyObject($value, string $shortName): bool
    {
        return $this->isA->isFuzzyObject($value, $shortName);
    }

    public function isSpecificObject($value, string $fullName): bool
    {
        return $this->isA->isSpecificObject($value, $fullName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsBool($value)
    {
        $this->assertIsType($value, 'bool');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsInt($value)
    {
        $this->assertIsType($value, 'int');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsFloat($value)
    {
        $this->assertIsType($value, 'float');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsString($value)
    {
        $this->assertIsType($value, 'string');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsArray($value)
    {
        $this->assertIsType($value, 'array');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsObject($value)
    {
        $this->assertIsType($value, 'object');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsCallable($value)
    {
        $this->assertIsType($value, 'callable');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsResource($value)
    {
        $this->assertIsType($value, 'resource');
    }

    /** @throws InvalidDataTypeException */
    public function assertIsFuzzyObject($value, string $shortName)
    {
        $this->assertIsType($value, 'fuzzy', $shortName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsSpecificObject($value, string $fullName)
    {
        $this->assertIsType($value, 'specific', $fullName);
    }

    /** @throws InvalidDataTypeException */
    public function assertIsType($value, $dataType, string $extra = null)
    {
        // We can just let PHP deal with user error when it comes to undefined method names :-/
        $isA = "is{$dataType}";

        // Thank you, PHP devs, for letting me throw on extra function parameters without even throwing a warning. /no-sarc
        if ($this->isA->$isA($value, $extra) !== true) {
            $aAn = in_array($dataType[0], ['a', 'e', 'i', 'o', 'u']) ? 'an' : 'a';
            // Handle data types that cannot be converted to strings.
            if (!in_array(gettype($value), ['string', 'int', 'float', 'double'])) {
                $value = substr((string) json_encode($value), 0, 15);
            }

            throw new InvalidDataTypeException("'$value' is not $aAn $dataType.");
        }
    }

    /**
     * Validates an array of values for the proper types; Laravel-esque.
     *
     * @param array $values
     * @param array $rules
     * @return bool true if completely valid.
     * @throws InvalidDataTypeException if one or more values are not the correct data type.
     */
    public function validate(array $values, array $rules): bool
    {
        $reasons = [];
        foreach ($values as $key => $value) {
            if (empty($rules[$key])) {
                continue;
            }

            $expectedType = $rules[$key];

            try {
                // Traditional values.
                if (in_array($expectedType, ['string', 'int', 'bool', 'float', 'array', 'object', 'callable', 'resource'])) {
                    $this->assertIsType($value, $expectedType);

                    continue;
                }
                // See if it is a specific class:
                elseif (strpos($expectedType, '\\') !== false) {
                    $this->assertIsSpecificObject($value, $expectedType);

                    continue;
                }

                $this->assertIsFuzzyObject($value, $expectedType);
            } catch (InvalidDataTypeException $e) {
                $reasons[$key] = "$key is not a valid $expectedType";
            }
        }

        if (!empty($reasons)) {
            $count = count($reasons);
            $s = $count > 1 ? 's': '';
            throw new InvalidDataTypeException("There were $count validation error{$s}.", $reasons);
        }

        return true;
    }
}
