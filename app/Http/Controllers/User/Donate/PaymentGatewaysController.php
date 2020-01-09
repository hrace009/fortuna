<?php

namespace App\Http\Controllers\User\Donate;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentGatewaysController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $gateways = PaymentGateway::enabled()->get();

        return PaymentGatewayResource::collection($gateways);
    }
}
