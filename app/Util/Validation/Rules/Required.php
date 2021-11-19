<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

class Required implements Rule
{
    public function run(Validator $validator, string $key, mixed $value): bool
    {
        $condition = isset($value) && !empty($value);

        if (!$condition) {
            $validator->bag->add(sprintf("The %s field is required", $key));
        }

        return $condition;
    }
}