<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator;

use InvalidArgumentException;
use Throwable;

class InvalidDataTypeException extends InvalidArgumentException
{
    /** @var array */
    protected $reasons;

    public function __construct($message, $reasons = [], $code = 0, Throwable $previous = null)
    {
        $this->reasons = $reasons;

        parent::__construct($message, $code, $previous);
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }
}
