<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEmailProvider implements Rule
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
        $allowedProviders = [
            'hotmail.com',
            'hotmail.com.br',
            'gmail.com',
            'outlook.com',
            'outlook.com.br',
            'live.com',
            'yahoo.com.br',
            'yahoo.com',
        ];

        $parseProvider = explode('@', $value);

        if (! in_array($parseProvider[1], $allowedProviders)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O endereço de email não é permitido.';
    }
}
