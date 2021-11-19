<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

class Equals implements Rule
{
    protected mixed $other;

    public function __construct(mixed $other)
    {
        $this->other = $other;
    }

    public function run(Validator $validator, string $key, mixed $value): bool
    {
        $condition = $value === $validator->getForm()[$this->other];

        if (!$condition) {
            $validator->bag->add(sprintf("The %s field should match %s", $key, $this->other));
        }

        return $condition;
    }
}