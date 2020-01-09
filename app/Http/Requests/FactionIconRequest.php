<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactionIconRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'guild' => 'required|string',
            'icon' => 'bail|required|image|mimes:jpeg,jpg,png,gif|mimetypes:image/jpeg,image/png,image/gif|dimensions:width=16,height=16',
        ];
    }
}
