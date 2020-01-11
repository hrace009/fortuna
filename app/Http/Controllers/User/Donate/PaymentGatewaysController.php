<?php

namespace App\Http\Controllers\User\Donate;

use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\PaymentGatewayResource;

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
