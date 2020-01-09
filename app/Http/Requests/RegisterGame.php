<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterGame extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (request()->user()->accounts->count() >= 1) {
            return response()->json(['error' => 'Limite de contas atigindo.']);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:32|unique:pw.users',
            'password' => 'required|string|min:6|confirmed',
            //'g_recaptcha_response' => 'required|recaptcha',
        ];
    }
}
