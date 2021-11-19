<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

class Max implements Rule
{
    private int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function run(Validator $validator, string $key, mixed $value): bool
    {
        $condition = false;

        if (is_string($value)) {
            $condition = strlen($value) <= $this->length;

            if (!$condition) {
                $validator->bag->add(sprintf("The %s field should be less than %s character(s) long", $key, $this->length));
            }
        }

        if (is_numeric($value)) {
            $condition = (int)$value <= $this->length;

            if (!$condition) {
                $validator->bag->add(sprintf("The %s field should be less than %s character(s) long", $key, $this->length));
            }
        }

        return $condition;
    }
}