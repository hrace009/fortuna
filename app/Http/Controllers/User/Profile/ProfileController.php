<?php

namespace App\Http\Controllers\User\Profile;

use App\Events\UpdateProfileDetails as ProfileUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return UserResource
     */
    public function update()
    {
        $data = request()->validate([
            'name'     => 'required|min:3|max:32',
            'gender'   => ['required', 'string', Rule::in(['male', 'female'])],
            'birthday' => 'bail|required|date_format:"d/m/Y"|minimumAge:13',
        ]);

        $user = tap(request()->user())->update($data);

        event(new ProfileUpdated);

        return new UserResource($user);
    }
}
