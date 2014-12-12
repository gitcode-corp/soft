<?php

namespace Soft\Authentication\Adapter;

interface Adapter
{
    /**
     * @return bool
     */
    public function authenticate();
}

