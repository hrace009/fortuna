<?php

namespace App\Http\Requests;

use App\Rules\ValidFacebookProfile;
use App\Rules\ValidTwitterProfile;
use App\Rules\ValidYouTubeChannel;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(User $user)
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
            'name'     => 'min:3',
            'gender'   => 'string|alpha|max:6',
            'birthday' => 'date_format:"Y-m-d"|before:-15 years',
            'facebook' => [new ValidFacebookProfile()],
            'twitter'  => [new ValidTwitterProfile()],
            'youtube'  => [new ValidYouTubeChannel()],
        ];
    }
}
