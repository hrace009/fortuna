<?php

namespace App\Http\Controllers\User\Donate;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\PaymentGateway;
use App\Models\Payments as Donate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentsController extends Controller
{
    /**
     * Process an order.
     *
     * @param Request $request
     * @return void
     */
    public function process(Request $request)
    {
        $gateway = PaymentGateway::bySlug($request->gateway)->firstOrFail();

        return $gateway->payment()->create($request->order_id);
    }

    /**
     * Updated the payment method.
     *
     * @param \Illuminate\Http\Request $request
     * @param Donate $donate The order that will be updated
     *
     * @return OrderResource|JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Donate $donate)
    {
        $this->validate($request, [
            'gateway' => [
                'required',
                'string',
                'exists:payment_gateways,slug',
            ],
        ]);

        if ((int) $donate->transaction_status !== 9) {
            return response()->json(['error' => 'Nao foi possivel atualizar o pedido.'], Response::HTTP_BAD_REQUEST);
        }

        $order = tap($donate)->update([
            'gateway' => $request->gateway,
        ]);

        return new OrderResource($order);
    }

    /**
     * Get an instant order feedback (PayPal only).
     *
     * @param Request $request
     * @param RegistryManager $registry
     * @return void
     */
    public function execute(Request $request)
    {
        $gateway = PaymentGateway::where('slug', $request->gateway)->firstOrFail();

        return $gateway->payment()->execute($request);
    }
}
