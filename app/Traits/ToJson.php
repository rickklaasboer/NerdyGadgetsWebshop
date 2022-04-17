<?php

namespace App\Traits;

trait ToJson
{
    /**
     * Json serialize
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}