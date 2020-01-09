<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProvider;
use App\Models\Payments;
use App\Models\Payments as Order;
use Facades\App\Services\Payment\PaymentStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PayPal implements PaymentProvider
{
    use PaymentService;

    /**
     * Create the transaction.
     *
     * @param Order $order
     * @return void
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create($orderId)
    {
        $order = Payments::where('order_id', $orderId)->first();

        $token = Str::uuid();

        $data = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => 'https://example.com/return',
                'cancel_url' => 'https://example.com/cancel',
                'brand_name' => 'Perfect World 4Fun',
                'locale' => 'pt-BR',
                'landing_page' => 'LOGIN',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ],
            'payer' => [
                'given_name' => $order->owner->name,
                'email_address' => $order->owner->email,
            ],
            'purchase_units' => [
                0 => [
                    'reference_id' => $token,
                    'description' => $this->description($order),
                    'custom_id' => $order->order_id,
                    'soft_descriptor' => 'PW4FUN',
                    'amount' => [
                        'currency_code' => 'BRL',
                        'value' => (float) $order->amount,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'BRL',
                                'value' => (float) $order->amount,
                            ],
                        ],
                    ],
                    'items' => [
                        0 => [
                            'name' => "{$order->present()->cashFormatted} Cubi Gold",
                            'description' => $this->description($order),
                            'sku' => '1',
                            'unit_amount' => [
                                'currency_code' => 'BRL',
                                'value' => (float) $order->amount,
                            ],
                            'quantity' => '1',
                            'category' => 'DIGITAL_GOODS',
                        ],
                    ],
                ],
            ],
        ];

        $request = new OrdersCreateRequest();
        $request->headers['prefer'] = 'return=representation';
        $request->body = $data;
        $client = PayPalClient::client();
        $response = $client->execute($request);

        $order->forceFill(['transaction_reference' => $token])->save();

        return response()->json([
                'code' => $response->result->id,
                'token' => $token,
        ], 200);
    }

    /**
     * Executes payment.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function execute(Request $request)
    {
        $captureRequest = new OrdersCaptureRequest($request->paymentID);
        $captureRequest->prefer('return=representation');
        try {
            $client = PayPalClient::client();
            $response = $client->execute($captureRequest);
        } catch (HttpException $e) {
            return response()->json(json_decode($e->getMessage(), true));
        }

        $paymentStatus = PaymentStatuses::standardize(strtolower($response->result->status));

        // Get the order
        $order = Order::where('transaction_reference', $request->token)->first();

        if (! $order) {
            logger("Pedido {$request->token} inexistente.");
        }

        $result = $response->result;
        $firstName = $result->payer->name->given_name;
        $lastName = $result->payer->name->surname;
        $purchase = $result->purchase_units[0]->payments->captures[0];

        $params = [
            'net_amount' => $purchase->seller_receivable_breakdown->net_amount->value,
            'transaction_status' => $paymentStatus,
            'transaction_code'   => $result->id,
            'payer_id' => $result->payer->payer_id,
            'payer_name' =>  "$firstName $lastName",
            'payer_email' =>  $result->payer->email_address,

        ];

        new NotificationHandler($order, $paymentStatus, $params);

        return ['success' => true];
    }

    public function notification($request)
    {
        $notification = new PayPalIPN();
        if (! app()->isProduction()) {
            $notification->useSandbox();
        }

        $verified = $notification->verifyIPN();

        if ($verified) {
            $paymentStatus = PaymentStatuses::standardize(strtolower($request['payment_status']));

            // Get the order
            $order = Order::where('order_id', $request['custom'])->first();

            if (! $order) {
                logger("Pedido {$request->token} inexistente.");
            }

            $params = [
                'transaction_status' => $paymentStatus,
            ];

            new NotificationHandler($order, $paymentStatus, $params);

            return $order;
        }

        header('HTTP/1.1 200 OK');
    }
}
