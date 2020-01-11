<?php

namespace App\Rules;

use Hashids;
use App\Game\User as UserGame;
use Illuminate\Contracts\Validation\Rule;

class ValidGameAccount implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $identifier = Hashids::decode($value['id'])[0];
        $name = $value['name'];

        $game = UserGame::where('ID', $identifier)->first();

        if (! $game) {
            return false;
        }

        if ($game->cid === request()->user()->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute escolhida não é válida.';
    }
}
