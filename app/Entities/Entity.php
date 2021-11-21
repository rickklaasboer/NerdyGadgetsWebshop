<?php

namespace App\Entities;

use InvalidArgumentException;

class Entity
{
    /**
     * Fill entity with data
     *
     * @param $data
     */
    public function fill($data)
    {
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Set attribute of entity
     *
     * NOTE: This requires attributes to be either protected or public
     *
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute(string $key, mixed $value)
    {
        $vars = get_object_vars($this);

        if (!array_key_exists($key, $vars)) {
            throw new InvalidArgumentException(
                sprintf("Invalid attribute '%s' given (is your attribute either public or protected?), expected: %s", $key, implode(', ', array_keys($vars)))
            );
        }

        $this->{$key} = $value;
    }
}