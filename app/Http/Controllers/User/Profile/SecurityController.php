<?php

namespace App\Http\Controllers\User\Profile;

use App\Events\TwoFactorDisabled;
use App\Events\TwoFactorEnabled;
use App\Http\Controllers\Controller;
use App\Repositories\TwoFactor\Authenticator;
use App\Repositories\TwoFactor\Method as TwoFactorMethod;
use App\Repositories\User\UserRepository;
use App\Services\RegistryManager;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use PragmaRX\Recovery\Recovery as BackupCode;

class SecurityController extends Controller
{
    // @TODO Can be improved.

    protected $users;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->users = $user;
    }

    public function index(Request $request, RegistryManager $manager)
    {
        // Return data based on user's choosed method
        return (new TwoFactorMethod())->process($request->user(), $manager->get('authenticator'));
    }

    /**
     * Enable two factor via an App.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticator(Request $request)
    {
        if ($request->user()->twofa_enabled) {
            return response()->json(
                ['twofa_enabled' => ['A autenticação de dois fatores já foi habilitada.']],
                403
            );
        }

        $user = $this->users->update($request->user()->id, [
            'twofactor' => collect($request->user()->twofactor)->union([
                'status' => true,
                'backup_codes' => $this->generateBackupCodes(),
            ]),
        ]);

        event(new TwoFactorEnabled($user));

        return $this->respondWithItem($user, new UserTransformer());
    }

    /**
     * Disable user's primary TFA method.
     *
     * @param Request $request
     *
     * @return void
     */
    public function disable(Request $request)
    {
        if (! $request->user()->twofactor['status']) {
            return response()->json(
                ['twofa_enabled' => ['A autenticação de dois fatores já está desabilitada.']],
                403
            );
        }

        $user = $this->users->update($request->user()->id, [
            'twofactor' => collect($request->user()->twofactor)->put('status', false),
        ]);

        event(new TwoFactorDisabled());

        return $this->respondWithItem($user, new UserTransformer());
    }

    public function getBackupCodes(Request $request)
    {
        return $request->user()->twofactor['backup_codes'];
    }

    protected function generateBackupCodes()
    {
        $generateBackupCode = (new BackupCode)
        ->setCount(10)
        ->setBlocks(2)
        ->setBlockSeparator('-')
        ->setChars(4)
        ->lowercase()
        ->toArray();

        $backupCodes = [];
        foreach ($generateBackupCode as $generatedCode) {
            $backupCodes[] = [
                'code' => $generatedCode,
                'consumed' => false,
            ];
        }

        return $backupCodes;
    }
}
