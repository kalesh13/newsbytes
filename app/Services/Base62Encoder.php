<?php

namespace App\Services;

use App\Contracts\Encoder;
use Tuupola\Base62 as TuupolaBase62Encoder;

class Base62Encoder implements Encoder
{
    /**
     * Encodes the given value
     * 
     * @param mixed $value
     * @return string
     */
    public function encode($value)
    {
        return (new TuupolaBase62Encoder())->encode($value);
    }

    /**
     * Decodes the given value
     * 
     * @param mixed $value
     * @return string
     */
    public function decode($value)
    {
        return (new TuupolaBase62Encoder())->decode($value);
    }
}
