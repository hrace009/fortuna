<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentNotifications extends Controller
{
    public function notification(Request $request)
    {
        $gateway = PaymentGateway::enabled()->whereSlug($request->gateway)->firstOrFail();

        return $gateway->payment()->notification($request->all());
    }
}
