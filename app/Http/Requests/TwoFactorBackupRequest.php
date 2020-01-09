<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as Validation;

class TwoFactorBackupRequest extends FormRequest
{
    /**
     * @var user
     */
    private $user;

    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     *
     * @return void
     */
    public function __construct(Validation $factory)
    {
        // Use backup code first
        $factory->extend(
            'backup_code',
            function ($attribute, $value, $parameters, $validator) {
                $backupCode = collect($this->user->twofactor['backup_codes'])->each(function ($item, $index) use ($value) {
                    if ($item['code'] == $value && $item['consumed']) {
                        $item['consumed'] = true;
                        $this->user->twofactor['backup_codes'] = $item;
                        $this->user->save();

                        return true;
                    }

                    return false;
                });

                return $backupCode;
            },
            'Código inválido'
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->user = User::findOrFail(decrypt(request()->twofactor_token));
        } catch (Exception $e) {
            return false;
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
            'backup_code' => 'bail|required|string|min:8|max:8|backup_code',
        ];
    }
}
