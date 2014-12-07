<?php

namespace Soft\Db\Exception;

class DriverManagerInvalidArgumentException extends \InvalidArgumentException
{
    public function __construct($message, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
