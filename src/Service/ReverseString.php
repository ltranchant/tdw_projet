<?php

namespace App\Service;

class ReverseString
{
    public function reverse(string $source): string
    {
        return strrev($source);
    }

}