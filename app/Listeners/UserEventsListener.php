<?php

namespace App\Listeners;

use App\Events\Associated;
use App\Events\SocialLogin;
use App\Events\ProfilePhoto;
use App\Events\RegisteredGame;
use App\Events\TwoFactorEnabled;
use App\Events\TwoFactorDisabled;
use App\Notifications\TfaEnabled;
use App\Events\GamePasswordChanged;
use App\Notifications\PasswordChanged;
use Illuminate\Auth\Events\PasswordReset;
use App\Events\UpdateProfileDetails as UpdateProfile;
use App\Events\PasswordChanged as EventPasswordChanged;
use App\Notifications\Associated as AssociatedNotification;
use App\Notifications\RegisteredGame as RegisteredGameNotification;
use App\Notifications\GamePasswordChanged as GamePasswordChangedNotification;

class UserEventsListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     *
     * @return void
     */
    public static function subscribe($events)
    {
        // $events->listen(Login::class, static function (Login $event) {
        //     activity()->log('Usuário efetuou o login.');
        // });

        // $events->listen(Logout::class, static function (Logout $event) {
        //     activity()->log('Usuário deslogou.');
        // });

        $events->listen(ProfilePhoto::class, static function (ProfilePhoto $event) {
            activity()->log('Usuário atualizou a foto de perfil.');
        });

        $events->listen(SocialLogin::class, static function (SocialLogin $event) {
            activity()->causedBy($event->user)->log('Usuário efetuou login por rede social ('.ucwords($event->provider).').');
        });

        $events->listen(UpdateProfile::class, static function (UpdateProfile $event) {
            activity()->log('Usuário atualizou os dados cadastrais.');
        });

        $events->listen(EventPasswordChanged::class, static function (EventPasswordChanged $event) {
            $event->getUser()->notify(new PasswordChanged($event->getUser()));
            activity()->log('Usuário alterou a senha.');
        });

        $events->listen(RegisteredGame::class, static function (RegisteredGame $registered) {
            $registered->getUser()->notify(new RegisteredGameNotification($registered->getUser()));
            activity()->log("Usuário criou uma conta de jogo ({$registered->getUser()->name}).");
        });

        $events->listen(GamePasswordChanged::class, static function (GamePasswordChanged $event) {
            $event->getUser()->notify(new GamePasswordChangedNotification($event->getUser()));
            activity()->log('Usuário alterou a senha.');
        });

        /*$events->listen(Associated::class, static function (Associated $event) {
            $event->getUser()->notify(new AssociatedNotification($event->getUser()));
            activity()->log("Usuário associou uma conta de jogo ({$event->getUser()->name}).");
        });*/

        $events->listen(PasswordReset::class, static function (PasswordReset $event) {
            activity()->causedBy($event->user)->log('Usuário resetou a senha .');
        });

        /*$events->listen(TwoFactorEnabled::class, static function (TwoFactorEnabled $event) {
            $event->getUser()->notify(new TfaEnabled($event->getUser()));
            activity()->log('Usuário habilitou a autenticação de dois fatores.');
        });

        $events->listen(TwoFactorDisabled::class, static function (TwoFactorDisabled $event) {
            activity()->log('Usuário desabilitou a autenticação de dois fatores.');
        });*/
    }
}
