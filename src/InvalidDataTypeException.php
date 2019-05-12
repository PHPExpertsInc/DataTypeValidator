<?php declare(strict_types=1);

/**
 * This file is part of DataTypeValidator, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
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
