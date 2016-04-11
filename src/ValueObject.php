<?php

namespace Antennaio\VO;

use InvalidArgumentException;

abstract class ValueObject
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    /**
     * Checks if value is valid.
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    abstract protected function validate($value);

    /**
     * Returns the raw value.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Returns the string representation of value.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
