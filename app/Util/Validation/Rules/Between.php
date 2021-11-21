<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

class Between implements Rule
{
    protected int $min;

    protected int $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function run(Validator $validator, string $key, mixed $value): bool
    {
        $condition = $value >= $this->min && $value <= $this->max;

        if (!$condition) {
            $validator->bag->add(sprintf("The %s field should be between %s and %s", $key, $this->min, $this->max));
        }

        return $condition;
    }
}