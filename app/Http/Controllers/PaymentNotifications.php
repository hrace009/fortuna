<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentNotifications extends Controller
{
    public function notification(Request $request)
    {
        $gateway = PaymentGateway::enabled()->whereSlug($request->gateway)->firstOrFail();

        return $gateway->payment()->notification($request->all());
    }
}
