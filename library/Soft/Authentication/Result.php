<?php

namespace Soft\Authentication;

use Soft\Authentication\Result\Result as ResultInterface;

class Result implements ResultInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var mixed
     */
    protected $identity;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @param  int     $code
     * @param  mixed   $identity
     * @param  array   $messages
     */
    public function __construct($code, $identity, array $messages = array())
    {
        $this->code     = (int) $code;
        $this->identity = $identity;
        $this->messages = $messages;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
