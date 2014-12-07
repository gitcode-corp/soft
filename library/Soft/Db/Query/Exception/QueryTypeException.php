<?php

namespace Soft\Db\Query\Exception;

class QueryTypeException extends \InvalidArgumentException
{
    public function __construct($message, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
