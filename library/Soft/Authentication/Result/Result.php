<?php

namespace Soft\Authentication\Result;

interface Result
{
    /**
     * @return int
     */
    public function getCode();

    /**
     * @return mixed
     */
    public function getIdentity();

    /**
     * @return array
     */
    public function getMessages();
}
