<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

class Email implements Rule
{
    public function run(Validator $validator, string $key, mixed $value): bool
    {
        $condition = filter_var($value, FILTER_VALIDATE_EMAIL);

        if (!$condition) {
            $validator->bag->add(sprintf("The %s field should be a valid email address", $key));
        }

        return $condition;
    }
}