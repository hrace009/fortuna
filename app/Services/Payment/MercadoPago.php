<?php

namespace App\Services\Payment;

use MercadoPago\SDK;
use MercadoPago\Item;
use MercadoPago\Payer;
use App\Models\Payments;
use MercadoPago\Payment;
use MercadoPago\Preference;
use Illuminate\Http\Response;
use App\Models\Payments as Order;
use App\Contracts\PaymentProvider;
use Facades\App\Services\Payment\PaymentStatuses;

class MercadoPago implements PaymentProvider
{
    use PaymentService;

    public function __construct()
    {
        SDK::initialize();
        SDK::setClientId(config('services.mercado_pago.client_id'));
        SDK::setClientSecret(config('services.mercado_pago.client_secret'));
    }

    public function create($orderId)
    {
        $order = Payments::where('order_id', $orderId)->first();

        $preference = new Preference();

        $payer = new Payer();
        $payer->email = $order->owner->email;
        $payer->identification = [
            'type' => 'CPF',
            'number' => $order->owner->document,
        ];

        $item = new Item();
        $item->id = $order->order_id;
        $item->title = $this->description($order);
        $item->description = $this->description($order);
        $item->quantity = 1;
        $item->unit_price = (float) $order->amount;

        $preference->items = [$item];
        $preference->payer = $payer;
        $preference->external_reference = (string) $order->order_id;
        $preference->payment_methods = [
            'excluded_payment_types' => [
                ['id' => 'ticket'],
            ],
        ];
        $preference->save();

        return response()->json(['success' => true, 'url' => $preference->init_point, 'preference_id' => $preference->id], 200);
    }

    public function notification($payload)
    {
        $payment = Payment::find_by_id($payload['id']);

        if (is_null($payment) || ! isset($payment)) {
            return new Response('IPN Handled.', 200);
        }

        $order = Order::where('order_id', $payment->external_reference)->first();

        if (! $order) {
            return new Response('MP.', Response::HTTP_CREATED);
        }

        $params = [
            'net_amount' => $payment->transaction_details->net_received_amount,
            'transaction_status' => PaymentStatuses::standardize($payment->status),
            'transaction_code' => $payment->id,
            'payer_id' => $payment->payer->id,
            'payer_name' =>  $payment->payer->name,
            'payer_email' =>  $payment->payer->email,
        ];

        new NotificationHandler($order, PaymentStatuses::standardize($payment->status), $params);

        return new Response('IPN Handled.', 200);
    }
}
