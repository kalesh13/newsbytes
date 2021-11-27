<?php

namespace App\Contracts;

interface Encoder
{
    /**
     * Encodes the given value
     * 
     * @param mixed $value
     * @return string
     */
    public function encode($value);

    /**
     * Decodes the given value
     * 
     * @param mixed $value
     * @return string
     */
    public function decode($value);
}
