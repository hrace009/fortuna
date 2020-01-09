<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class ReCaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=> [
                    'secret'  => config('services.google.recaptcha.secret_key'),
                    'response'=> $value,
                 ],
            ]
        );

        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Certifique-se de que você não é um robô.';
    }
}
