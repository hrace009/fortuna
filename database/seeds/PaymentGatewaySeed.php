<?php

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->getGateways()->each(function ($gateway) {
            return PaymentGateway::create($gateway);
       });
    }

    public function getGateways() : Collection
    {
        return collect([
            [
                'name' => 'PagHiper',
                'slug' => 'paghiper',
                'payment_methods' => json_encode(['boleto']),
                'enabled' => true,
                'description' => '',
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'payment_methods' => json_encode(["visa", "mastercard", "elo", "hiper"]),
                'enabled' => true,
                'delivery_time' => 'Em alguns minutos o pagamento é aprovado, e seu Gold é liberado em seguida.',
                'description' => 'Até 3x sem juros no cartão',
            ],
            [
                'name' => 'Mercado Pago',
                'slug' => 'mercadopago',
                'payment_methods' => json_encode(["visa", "mastercard", "elo", "hiper"]),
                'enabled' => true,
                'delivery_time' => 'Em alguns minutos o pagamento é aprovado, e seu Gold é liberado em seguida.',
                'description' => 'Até 12x sem juros no cartão',
            ]
        ]);
    }
}
