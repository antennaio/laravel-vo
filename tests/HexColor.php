<?php

namespace Antennaio\VO\Test;

use Antennaio\VO\ValueObject;
use InvalidArgumentException;

class HexColor extends ValueObject
{
    /**
     * Checks if value is a valid HEX color.
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate($value)
    {
        $colorCode = ltrim($value, '#');

        if (!ctype_xdigit($colorCode) || (strlen($colorCode) !== 6 && strlen($colorCode) !== 3)) {
            throw new InvalidArgumentException('HEX color is invalid: '.$value);
        }
    }
}
