<?php

namespace App\Inspections;

use Exception;

class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/u', $body)) {
            throw new Exception('This reply contains spam');
        }
    }
}
