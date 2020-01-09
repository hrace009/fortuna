<?php

namespace App\Mail;

use App\Game\BankReset as GameStorehouseReset;
use Hashids;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BankResetConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $storehouse;

    /**
     * Create a new message instance.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(GameStorehouseReset $storehouse)
    {
        $this->storehouse = $storehouse;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $account = Hashids::encode($this->storehouse->game_account['id']);

        return $this->markdown('emails.user.game.bank-reset', [
            'url' => url("/minha-conta/contas-vinculadas/visualizar-conta/{$account}?service_token={$this->storehouse->token}"),
            'data'  => $this->storehouse,
        ]);
    }
}
