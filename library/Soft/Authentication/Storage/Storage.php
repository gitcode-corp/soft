<?php

namespace Soft\Authentication\Storage;

interface Storage
{
    /**
     * Returns true if and only if storage is empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Returns the contents of storage
     * 
     * @return mixed
     */
    public function read();

    /**
     * @param  mixed $contents
     * @return void
     */
    public function write($contents);

    /**
     * @return void
     */
    public function clear();
}