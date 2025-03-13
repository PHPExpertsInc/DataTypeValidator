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

namespace PHPExperts\DataTypeValidator;

use InvalidArgumentException;
use Throwable;

class InvalidDataTypeException extends InvalidArgumentException
{
    /**
     * @param string $message
     * @param array<mixed> $reasons
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, protected array $reasons = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<mixed>
     */
    public function getReasons(): array
    {
        return $this->reasons;
    }
}
