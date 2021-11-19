<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;

interface Rule
{
    public function run(Validator $validator, string $key, mixed $value): bool;
}