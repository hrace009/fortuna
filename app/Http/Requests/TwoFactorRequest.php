<?php

namespace App\Http\Requests;

use App\Models\User;
use Cache;
use Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Factory as Validation;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorRequest extends FormRequest
{
    /**
     * @var user
     */
    private $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->user = ! auth()->user() ? User::findOrFail(decrypt(request()->twofactor_token)) : auth()->user();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     *
     * @return void
     */
    public function __construct(Validation $factory)
    {
        $google2fa = new Google2FA;

        // Check if token is valid
        $factory->extend(
            'valid_token',
            function ($attribute, $value, $parameters, $validator) use ($google2fa) {
                $secret = Crypt::decrypt($this->user->twofactor['secret']);

                return $google2fa->verifyKey($secret, $value);
            },
            'Código inválido'
        );

        // Check if token waas used before
        $factory->extend(
            'used_token',
            function ($attribute, $value, $parameters, $validator) {
                $key = $this->user->id.':'.$value;

                return ! Cache::has($key);
            },
            'Não é possível reutilizar este código'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'otp' => 'bail|required|min:6|max:6|valid_token|used_token',
        ];
    }
}
