<?php

namespace App\Rules;

use App\Inspections\Protection;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class Unkiddief implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            return ! resolve(Protection::class)->detect($value);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
    }
}
