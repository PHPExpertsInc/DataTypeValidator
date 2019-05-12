<?php declare(strict_types=1);

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
